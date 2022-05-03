<?php
namespace Keygen\License\Traits;

use Keygen\cURL\Connection;
use Keygen\Util\Settings;

trait LicenseConnectionTrait
{
    /**
     * Returns a Connection that is setup for a license API connection.
     *
     * @param bool $useBearer
     * @return Connection
     * @throws \Exception
     * @since 1.0
     */
    public static function getConnection($useBearer = true)
    {
        $connection = new Connection();
        if($useBearer) {
            $connection->setBearer(Settings::get('bearer'));
        }

        $connection->setURI('licenses');
        return $connection;
    }
}