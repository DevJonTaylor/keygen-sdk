<?php
namespace Keygen\User\Traits;

use Keygen\User\User;
use Exception;

trait GetUser
{

    /**
     * This will connect to Keygen and add the attributes to a User Object then return the User Object.
     * If there is an issue I.E. "Authentication", "User Not Found" an Exception will be thrown.
     *
     * @param $id
     * @throws Exception
     * @return bool|User
     * @since 1.0
     */
    public static function getUser($id)
    {
        $con = User::getConnection()
            ->setURI($id);
        $results = json_decode($con->get());
        if(isset($results->data)) {
            return new User($results);
        } else {
            $msg = $results->errors[0]->detail;
            switch($msg) {
                case 'The requested resource was not found (please ensure your API endpoint is correct)':
                    throw new Exception($msg . ' :: ' . $con->buildURL());
                    break;
                default:
                    throw new Exception($msg);
                    break;
            }
        }
    }
}