<?php
namespace Keygen\User\Traits;

use Keygen\User\User;
use stdClass;
use Exception;

trait NewUser
{
    protected static $required = array(
        'firstName',
        'lastName',
        'email',
        'password'
    );

    /**
     * $this will create a new user within Keygen.sh's server.
     *
     *
     * @param $attributes
     * @return User
     * @throws Exception
     * @since 1.0
     */
    public static function newUser($attributes)
    {
        $newUser = new stdClass();
        $newUser->data = new stdClass();
        $newUser->data->type = 'users';
        $newUser->data->attributes = new stdClass();
        $attributesType = gettype($attributes);
        switch($attributesType) {
            case 'object':
                return self::newUser(get_object_vars($attributes));
                break;
            case 'array':
                foreach($attributes as $name => $value) {
                    switch(strtolower($name)) {
                        case 'firstname':
                            $newUser->data->attributes->firstName = $value;
                            break;
                        case 'lastname':
                            $newUser->data->attributes->lastName = $value;
                            break;
                        case 'email':
                            $newUser->data->attributes->email = $value;
                            break;
                        case 'password':
                            $newUser->data->attributes->password = $value;
                            break;
                        case 'metadata':
                            $newUser->data->attributes->metadata = new stdClass();
                            foreach((array) $value as $k => $v) {
                                $newUser->data->attributes->metadata->{$k} = $v;
                            }
                            break;
                    }
                }
                break;
        }


        $hasAllRequirements = true;
        $missing = array();
        foreach(self::$required as $name) {
            if(!isset($newUser->data->attributes->{$name})) {
                $hasAllRequirements = false;
                $missing[] = $name;
            }
        }

        if(!$hasAllRequirements) {
            throw new Exception('Missing ' . implode(', ', $missing) . ' attributes.');
        }


        $results = json_decode(User::getConnection()
            ->setPostParams(json_encode($newUser))
            ->post());
        if(isset($results->data)) {
            $user = new User($results);
            self::$instance[$user->id] = $user;
            return $user;
        } else {
            throw new Exception($results->errors[0]->detail);
        }
    }
}