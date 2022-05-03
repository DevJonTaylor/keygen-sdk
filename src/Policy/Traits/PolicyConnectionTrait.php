<?php
namespace Keygen\Policy\Traits;

use Keygen\cURL\Connection;
use Keygen\Util\Settings;

trait PolicyConnectionTrait
{
    /**
     * Quickly grabs the connection needed for Policies.
     *
     * @param bool $useBearer
     * @return Connection
     * @throws \Exception
     * @since 1.0
     */
    public static function getConnection($useBearer = true)
    {
        $connection = new Connection();
        $connection->setURI('policies');

        if($useBearer === true) $connection->setBearer(Settings::get('bearer'));

        return $connection;
    }
}