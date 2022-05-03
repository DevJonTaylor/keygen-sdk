<?php
namespace Keygen\User\Traits;

use Keygen\User\User;
use Keygen\License\License;
use Exception;

trait GetRelationshipTrait
{
    /**
     * Finds all of the objects within the requested relationship and returns them inside an array.
     *
     * @param string $relationship which relationship you are looking to pull.
     * @param string|User $user UserID or a User Object.
     * @return array
     * @throws Exception
     * @since 1.0
     */
    protected function getRelationship($relationship, $user)
    {
        $results = json_decode(User::getConnection()
            ->setURI($user instanceof User ? $user->id : $user, $relationship)
            ->get());
        if(!isset($results->data)) {
            throw new Exception($results->errors[0]->detail);
        }

        return $results->data;
    }

    /**
     * Returns an array with all currently assigned Licenses.  Each node is a License Object.
     *
     * @return array|null|License All objects are Licenses.
     * @throws Exception
     * @since 1.0
     */
    public function getLicenses()
    {
        $return = array();
        $licenses = $this->getRelationship('licenses', $this);

        switch(count($licenses)) {
            case 0:
                return null;
            case 1:
                return new License($licenses[0]);
            default:
                foreach($licenses as $license) {
                    $return[] = new License($license);
                }
                return $return;
        }
    }
}