<?php
namespace Keygen\License\Traits;

use Keygen\License\License;
use Exception;
use Keygen\User\User;
use Keygen\Policy\Policy;
trait CreateLicenseTrait
{

    /**
     * Creates a new License inside of KeygenTest.sh API and then returns a License Object.
     *
     * @param string|User $userId
     * @param string|Policy $policyId
     * @return License
     * @throws Exception
     * @since 1.0
     */
    public static function newLicense($userId, $policyId)
    {
        if($userId instanceof User) {
            $userEmail = "({$userId->email})";
            $user = array(
                'data' => array(
                    'type' => 'users',
                    'id' => $userId->id
                )
            );

        } elseif(is_string($userId)) {
            $u = User::getInstance($userId);
            $userEmail = "({$u->email})";
            $user = array(
                'data' => array(
                    'type' => 'users',
                    'id' => $userId
                )
            );
        } else {
            throw new Exception('::newLicenses($userId) expects either instance of User or String.');
        }

        if($policyId instanceof Policy) {
            $policyName = "{$policyId->name}";
            $policy = array(
                'data' => array(
                    'type' => 'policies',
                    'id' => $policyId->id
                )
            );

        } elseif(is_string($policyId)) {
            $p = Policy::getInstance($policyId);
            $policyName = "{$policyId->name}";
            $policy = array(
                'data' => array(
                    'type' => 'policies',
                    'id' => $policyId
                )
            );
        } else {
            throw new Exception('::newLicenses($policyId) expects either instance of Policy or String.');
        }

        $postParameter = array(
            'data' => array(
                'type' => 'licenses',
                'attributes' => array(
                    'name' => "{$policyName} {$userEmail}",
                    'protected' => false
                ),
                'relationships' => array(
                    'user' => $user,
                    'policy' => $policy
                )
            )
        );

        $results = json_decode(License::getConnection()
            ->setPostParams(json_encode($postParameter))
            ->post());
        if(isset($results->data)) {
            $license = new License($results);
            self::$instance[$license->id] = $license;
            return $license;
        } else {
            throw new Exception($results->errors[0]->detail);
        }
    }
}