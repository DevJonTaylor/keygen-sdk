<?php


namespace Keygen\License\Traits;


use Exception;
use Keygen\License\License;
use Keygen\Policy\Policy;

trait GetRelationshipTrait
{
    /**
     * Finds all of the objects within the requested relationship and returns them inside an array.
     *
     * @param string $relationship which relationship you are looking to pull.
     * @param string|License $license UserID or a User Object.
     * @return array
     * @throws Exception
     * @since 1.0
     */
    protected function getRelationship($relationship, $license)
    {
        $results = json_decode(User::getConnection()
            ->setURI($license instanceof License ? $license->id : $license, $relationship)
            ->get());
        if(!isset($results->data)) {
            throw new Exception($results->errors[0]->detail);
        }

        return $results->data;
    }

    /**
     * Returns an array with all currently assigned Licenses.  Each node is a License Object.
     *
     * @return Policy All objects are Licenses.
     * @throws Exception
     * @since 1.0
     */
    public function getPolicy()
    {
        if($this instanceof License) {
            if($this->id !== null)
                return new Policy($this->getRelationship('policy', $this));
        }

        return null;
    }
}
