<?php


namespace Keygen\User\Traits;


use Exception;
use Keygen\User\User;

trait SearchTrait
{
    protected static $page = 1;
    protected static $limit = 100;
    protected static $total = 0;
    protected static $list = array();
    protected static $strict = false;
    
    protected static function filterThisUser($user, $attribute, $value)
    {
        if (isset($user->attributes->{$attribute}) !== false) {
            $attributeValue = $user->attributes->{$attribute};
        } elseif (isset($user->attributes->metadata->{$attribute}) !== false) {
            $attributeValue = $user->attributes->metadata->{$attribute};
        } else {
            return false;
        }
    
        if (self::$strict === true) {
            return $attributeValue === $value;
        } else {
            return strpos($attributeValue, $value);
        }
    }
    
    protected static function getUsers()
    {
        $raw = self::getConnection()
            ->setParameters('page[size]', self::$limit)
            ->setParameters('page[number]', self::$page)
            ->get();
        
        return json_decode($raw);
    }
    
    public static function search($attribute, $value, $strict = false)
    {
        self::$strict = $strict;
        
        $results = self::getUsers();
        
        if(isset($results->data) !== false) {
            $arrayOfUsers = $results->data;
            
            foreach($arrayOfUsers as $user) {
                if(self::filterThisUser($user, $attribute, $value) === true) {
                    self::$list[] = new User(array('data' => $user));
                }
            }
            
            if(isset($results->links) !== false) {
                if(isset($results->links->next) !== false) {
                    if($results->links->next !== null) {
                        self::$page += 1;
                        return self::search($attribute, $value, $strict);
                    }
                }
            }
            
            return self::$list;
        } else {
            if(isset($results->errors[0]->detail))
                switch($results->errors[0]->detail) {
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
