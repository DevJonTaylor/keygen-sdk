<?php
namespace Keygen\User\Traits;

use Keygen\cURL\Connection;
use Exception;
use Keygen\Util\Debug;

trait UserAuthenticationTrait
{
    /**
     * This performs a token request.  If successful it will return either
     * returnBearer = false |   true on success and false on failure.
     * returnBearer = true  |   false on failure and Bearer Token on success.
     *
     * @param string    $username       The email that is used to authenticate.
     * @param string    $password       Password for the user account.
     * @return bool|string
     * @throws Exception If there are errors within the response.  Other than Denied authentication.
     * @since 1.0
     */
    public static function basicAuthentication($username, $password)
    {
        $auth = base64_encode("{$username}:{$password}");
        $connection = new Connection();
        $results = json_decode($connection
            ->setURI('tokens')
            ->setHeader("Authorization: Basic {$auth}")
            ->post());

        if(isset($results->data)) {
             return (object) array(
                'Token' => $results->data->attributes->token,
                 'UserId' => $results->data->relationships->bearer->data->id
            );
        } else {
            if(isset($results->errors[0]->detail))
                switch(strtolower($results->errors[0]->detail)) {
                    case 'credentials must be valid':
                        return false;
                        break;
                    default:
                        throw new Exception($results->errors[0]->detail);
                        break;
                }
        }
    }
}
