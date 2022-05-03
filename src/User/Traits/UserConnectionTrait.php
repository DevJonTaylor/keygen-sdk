<?php
namespace Keygen\User\Traits;

use Exception;
use Keygen\cURL\Connection;
use Keygen\Util\Settings;

trait UserConnectionTrait
{
    /**
     * Quickly grabs the connection needed for Users.
     *
     * @param bool $useBearer
     * @return Connection
     * @throws Exception
     * @since 1.0
     */
    public static function getConnection($useBearer = true)
    {
        $connection = new Connection();
        $connection->setURI('users');

        if($useBearer === true) $connection->setBearer(Settings::get('bearer'));

        return $connection;
    }
}
