<?php

namespace Keygen\Model\Traits;

trait GetterSetterTrait
{
    /**
     * Holds the getter/setter data.
     * @var array $data
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public array $data = array();

    /**
     * @param string $name
     * @return bool
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __isset(string $name): bool
    {
        return key_exists($name, $this->data);
    }

    /**
     * @param string $name
     * @return void
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }

    /**
     * @param string $name
     * @return string|null
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __get(string $name): string|null
    {
        return isset($this->{$name}) ? $this->data[$name] : null;
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __set(string $name, string $value): void
    {
        $this->data->{$name} = $value;
    }

    /**
     * @param string $name
     * @param $default
     * @return string|null
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function getOr(string $name, $default = null): string|null
    {
        return !isset($this->{$name}) ? $default : $this->{$name};
    }

    /**
     * @return array
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __serialize(): array
    {
        return $this->data;
    }

    /**
     * @return string
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function __toString(): string
    {
        return json_encode($this->toObject());
    }

    /**
     * @return object
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    public function toObject(): object
    {
        return (object) $this->data;
    }
}