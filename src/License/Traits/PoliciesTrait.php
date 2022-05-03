<?php
namespace Keygen\License\Traits;


use Keygen\License\License;
use Keygen\Policy\Policy;
use Keygen\Util\Debug;

trait PoliciesTrait
{
    public function changePolicy($policy)
    {
        $results = json_decode(License::getConnection()
            ->setURI($this->{'id'}, 'policy')
            ->setPostParams(json_encode(array(
                'data' => array(
                    'type' => 'policies',
                    'id' => $policy instanceof Policy ? $policy->id : $policy
                ))
            ))
            ->put());

        if(isset($results->data)) {
            $this->sortingData($results);
        } else {
            Debug::displayVar($results);
        }
    }

    public function getPolicies()
    {

    }
}
