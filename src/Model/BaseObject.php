<?php
namespace Keygen\Model;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;
use Keygen\Util\Debug;

class BaseObject implements ArrayAccess, Countable, JsonSerializable, IteratorAggregate
{
    const ENDPOINT = '';

    const READ_WRITE = array(
        'id' => false,
        'type' => false,
    );

    protected $_index = array();
    protected $_values = array();
    protected $_changes = array();
    protected $_response = array();

    public function __construct($options = array())
    {
        foreach($options as $k => $v) {
            switch(strtolower($k)) {
                case 'readonly':
                    switch(gettype($v)) {
                        case 'string':
                            $this->readOnly($v);
                            break;
                        case 'array':
                            foreach($v as $readOnly) {
                                $this->readOnly($readOnly);
                            }
                            break;
                    }
                    break;
                case 'data':
                    $this->sortingData($v);
                    break;
            }
        }
    }

    protected function sortingData($data)
    {
        $readyData = array();
        switch(gettype($data)) {
            case 'string':
                $readyData = json_decode($data, true);
                break;
            case 'array':
                $readyData = $data;
                break;
            case 'object':
                $readyData = json_decode(json_encode($data), true);
                break;
        }

        $this->_response = $readyData;
        $this->parseResponse();
    }

    public function parseResponse($currentArray = null, $path = array())
    {
        if($currentArray === null) {
            $currentArray = $this->_response;
        }

        foreach($currentArray as $key => $value) {
            if($key === 'relationships') continue;
            if(!$this->readOnly($key)) {
                $path[] = $key;
                $this->_index[$key] = implode('.', $path);
                if(is_array($value)) {
                    $this->parseResponse($value, $path);
                } else {
                    $this->addData($key, $value);
                }
                array_pop($path);
            }
        }
    }

    public function addData($name, $value)
    {
        $this->_values[$name] = $value;
    }

    public function readOnly($name)
    {
        if(array_key_exists($name, self::READ_WRITE)) {
            return self::READ_WRITE[$name];
        } else {
            return null;
        }
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_values);
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_values);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return array_key_exists($offset, $this->_values) ? $this->_values[$offset] : null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->_values);
    }

    /**
     * run when writing data to inaccessible members.
     *
     * @param $name string
     * @param $value mixed
     * @return void
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     * @since 1.0
     */
    public function __set($name, $value)
    {
        if(array_key_exists($name, self::READ_WRITE)) {
            if(self::READ_WRITE[$name] === true) {
                $path = $this->getPath($name);
                if($path !== null) {
                    $this->readPath($path, $value);
                }
            }
        } else {
            $path = $this->getPath($name);
            if($path !== false) {
                $this->readPath($path, $value);
            } else {
                $path = "data.attributes.metadata.{$name}";
                $this->_index[$name] = $path;
                $this->readPath($path, $value);
            }
        }
    }

    protected function getPath($name)
    {
        if(array_key_exists($name, $this->_index)) {
            return $this->_index[$name];
        }

        return false;
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     * @since 1.0
     */
    public function __get($name)
    {
        if(array_key_exists($name, $this->_values)) return $this->_values[$name];

        $path = $this->getPath($name);
        if(!$path) return null;

        return $this->readPath($path);
    }

    /**
     * utilized for checking if a variable is set from an inaccessible positiion.
     *
     * @param mixed $name
     * @return boolean
     * @since 2.0
     */
    public function __isset($name)
    {
        return isset($this->_values[$name]);
    }

    public function readPath($path, $value = null)
    {
        $updateRoot = &$this->_changes;
        $update = &$updateRoot;
        $currentPath = &$this->_response;

        $pathArray = !is_array($path) ? explode('.', $path) : $path;
        $lastKey = array_pop($pathArray);

        foreach($pathArray as $key) $currentPath = &$currentPath[$key];

        if($value !== null) {
            foreach($pathArray as $key) {
                if(!array_key_exists($key, $update)) {
                    if($key === 'metadata') {
                        $update[$key] = $this->_response['data']['attributes']['metadata'];
                    } else {
                        $update[$key] = array();
                    }
                }
                $update = &$update[$key];
            }

            $currentPath[$lastKey] = $value;
            $update[$lastKey] = $value;
            $this->_values[$lastKey] = $value;
        }
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     * @since PHP 5.6.0
     *
     * @return array
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return $this->_values;
    }

    public function save()
    {
        return $this->_changes;
    }
}
