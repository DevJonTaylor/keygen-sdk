<?php

namespace Keygen\Exceptions\Traits;

use Keygen\Exceptions\Exception as KException;

trait BadConfigPathTrait
{
    public static function BadConfigPath(string $pathToConfig): KException
    {
        $message = "`${pathToConfig}` is not a proper path.";
        return new KException($message, 10);
    }
}