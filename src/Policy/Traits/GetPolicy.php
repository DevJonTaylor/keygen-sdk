<?php
namespace Keygen\Policy\Traits;

use Keygen\Policy\Policy;
use Exception;
use Keygen\Util\Debug;
use Keygen\Util\Settings;

trait GetPolicy
{
    /**
     * Retrieves the policy object from KeygenTest.sh API and returns it.
     *
     * @param $id
     * @return Policy
     * @throws Exception
     * @since 1.0
     */
    public static function getPolicy($id)
    {
        $results = json_decode(Policy::getConnection()->setURI($id));
        if(isset($results->data)) {
            return new Policy($results);
        } else {
            throw new Exception($results->errors[0]->detail);
        }
    }
}