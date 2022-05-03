<?php
namespace Keygen\Policy\Traits;

use Keygen\Policy\Policy;
use Exception;

trait ListPoliciesTrait
{
    /**
     * Sends a request to Keygen.sh for a list of policies based on the page number and page size provided.
     *
     * @param int $pageNumber
     * @param int $pageLimit
     * @return array
     * @throws Exception
     * @since 1.0
     */
    public static function getListOfPolicies($pageNumber = 1, $pageLimit = 100)
    {
        $results = json_decode(Policy::getConnection()
            ->setParameters(array('page[size]' => $pageLimit, 'page[number]' => $pageNumber))
            ->get());

        if(isset($results->data)) {
            $return = array();
            foreach($results->data as $policy) {
                $newPolicy = new Policy($policy);
                $return[] = $newPolicy;
            }

            return $return;
        } else {
            throw new Exception($results->errors[0]->detail);
        }
    }
}