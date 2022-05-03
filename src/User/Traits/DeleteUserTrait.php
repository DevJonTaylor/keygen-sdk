<?php


namespace Keygen\User\Traits;


use Exception;
use Keygen\User\User;

trait DeleteUserTrait
{
    public static function deleteUser($id)
    {
        try {
            $con = User::getConnection()
                ->setURI($id);
            $results = json_decode($con->delete());
            return $results;
        } catch(Exception $err) {
            return (object) $err;
        }
    }
}