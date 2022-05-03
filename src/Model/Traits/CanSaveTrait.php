<?php

namespace Keygen\Model\Traits;

use Keygen\Util\Debug;

trait CanSaveTrait
{
    /**
     * @var array
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected array $propertiesToSave = array();

    /**
     * @param string|array $name
     * @return void
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected function add(string|array $name): void
    {
        if(gettype($name) === 'array')
            foreach(array_keys($name) as $key)
                $this->add($key);
        else
            $this->propertiesToSave[$name] = true;
    }

    /**
     * @param string $name
     * @return void
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected function remove(string $name): void
    {
        unset($this->propertiesToSave[$name]);
    }

    /**
     * @param string $name
     * @return bool
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected function canSave(string $name): bool
    {
        return key_exists($name, $this->propertiesToSave);
    }

    /**
     * @param array $data
     * @return array
     * @version 1.1
     * @author Jon Taylor
     * @date 5/3/2022
     */
    protected function removeRestricted(array $data): array
    {
        $newArray = array();
        foreach(array_keys($this->propertiesToSave) as $key)
            $newArray[$key] = $data[$key];

        return $newArray;
    }
}