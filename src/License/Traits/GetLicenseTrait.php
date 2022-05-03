<?php
namespace Keygen\License\Traits;


use Keygen\License\License;
use Keygen\Util\Debug;

trait GetLicenseTrait
{
    /**
     * Returns a License object that was retrieved from the Keygen.sh API.
     *
     * @param string $id
     * @return License
     * @throws \Exception
     * @since 1.0
     */
    public static function getLicense($id)
    {
        //return new License(json_decode(License::getConnection()->setURI($id)->get()));
        return new License(Debug::getMock()->license);
    }
}