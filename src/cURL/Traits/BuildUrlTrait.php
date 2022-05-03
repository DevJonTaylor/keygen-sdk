<?php
namespace Keygen\cURL\Traits;

use Exception;
use stdClass;

/**
 * Trait BuildUrlTrait
 * The main components of building a url will be listed below.
 * {SECURE}     -> http/https
 * {BASE}       -> sub(www/api/actual)/domain(google/twitter/apple)/top level(com/net/org) www.google.com
 * {URI}        -> /other/directives/go/here
 * {PARAMETERS} -> ?examples=parameters&go=here
 *
 * @package KeygenTest\cURL
 * @since 1.0
 */
trait BuildUrlTrait
{
    /**
     * {SECURE} is stored here.
     * @var bool
     * @since 1.0
     */
    protected $secure = true;

    /**
     * {BASE} is stored here.
     * @var string
     * @since 1.0
     */
    protected $base;

    /**
     * {URI} is stored here.
     * @var array
     * @since 1.0
     */
    protected $uri = array();

    /**
     * {PARAMETERS} are stored here.
     * @var array
     * @since 1.0
     */
    protected $parameters = array();

    /**
     * @param string $base
     * @return $this
     * @throws Exception
     * @since 1.0
     */
    public function setBase($base)
    {
        if(!is_string($base)) {
            throw new Exception('::setBase accepts String type.  Received ' . gettype($base) . '.');
        }
        $this->base = preg_replace('/\/$/', '', $base);
        return $this;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param bool $secure
     * @return $this
     * @throws Exception
     * @since 1.0
     */
    public function setSecure($secure)
    {
        if(!is_bool($secure)) {
            throw new Exception('::setSecure accepts Booleans.  Received ' . gettype($secure) .'.');
        }
        $this->secure = $secure;
        return $this;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * This method will allow an array, integer, or string type parameter.
     *
     * @param array|string|integer $uri is run through urlencode function before added.
     * @return $this for chaining purposes.
     * @throws Exception
     * @since 1.0
     */
    public function setURI(...$uris)
    {
        foreach($uris as $uri) {
            $type = gettype($uri);
            switch(gettype($uri)) {
                case 'array':
                    foreach($uri as $value) {
                        $this->setURI($value);
                    }
                    break;
                case 'string':
                case 'integer':
                    $this->uri[] = urlencode($uri);
                    break;
                default:
                    throw new Exception("::setURI Can take Strings, Integers, or Arrays.  Received {$type}.");
                    break;
            }
        }

        return $this;
    }

    /**
     * @return array
     * @since 1.0
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * This sets the parameters.
     * If $name receives stdClass it will convert it to an array and recursively send it back through.
     * If $name receives array it will use foreach to get the name and value and recursively send it back through.
     *
     * @param string|integer|array|stdClass $name
     * @param null|string $value is run through urlencode function before added.
     * @throws Exception
     * @return $this For chaining purposes
     * @since 1.0
     */
    public function setParameters($name, $value = null)
    {
        $nameType = gettype($name);
        $valueType = gettype($value);
        switch($nameType) {
            case 'object':
                $this->setParameters(get_object_vars($name));
                break;
            case 'array':
                foreach($name as $n => $v) {
                    $this->setParameters($n, $v);
                }
                break;
            case 'string':
            case 'integer':
                switch($valueType) {
                    case 'string':
                    case 'integer':
                        $this->parameters[] = $name.'='.urlencode($value);
                        break;
                    default:
                        throw new Exception("::setParameters(\$value) expected a String not {$valueType}.");
                        break;
                }
                break;
            default:
                $msg = "::setParameters(\$name) expected String, Integer, or Array.  Received {$nameType}";
                throw new Exception($msg);
                break;
        }

        return $this;
    }

    /**
     * @return array
     * @since 1.0
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * This constructs the URL from the variables provided and returns a string.
     * @return string
     * @since 1.0
     */
    public function buildURL()
    {
        $url = $this->secure === false ? 'http://' : 'https://';
        $url .= $this->base;
        if(count($this->uri) > 0) {
            $url .= '/' . implode('/', $this->uri);
        }
        if(count($this->parameters) > 0) {
            $url .= '?' . implode('&', $this->parameters);
        }
        return $url;
    }
}