<?php
/**
 * User: Jon
 * Date: 2/26/2019
 */
namespace Keygen\User;

use Exception;
use Keygen\Model\UserModel;
use Keygen\User\Traits\ChangePasswordTrait;
use Keygen\User\Traits\DeleteUserTrait;
use Keygen\User\Traits\GetUser;
use Keygen\User\Traits\NewUser;
use Keygen\User\Traits\SearchTrait;
use Keygen\User\Traits\UserConnectionTrait;
use Keygen\User\Traits\GetRelationshipTrait;
use Keygen\User\Traits\UserAuthenticationTrait;
use Keygen\Util\Debug;

/**
 * @property        string          firstName
 * @property        string          lastName
 * @property        string          email
 * @property        string          fullName
 * @property        string          company
 * @property        string          institution
 * @property        string          id
 * @property-read   string          role
 * @property-read   string          self
 * @property-read   string          type
 * @property-read   string          created
 * @property-read   string          updated
 * @property        boolean         firstTime
 * @property        boolean         resetPassword
 * @property        string          resetToken
 * @since 1.0
 */
class User extends UserModel
{
    use UserAuthenticationTrait,
        GetUser,
        NewUser,
        UserConnectionTrait,
        GetRelationshipTrait,
        ChangePasswordTrait,
        DeleteUserTrait,
        SearchTrait;

    protected static $instance = array();
    protected static $lastUserId = 0;

    /**
     * This will ensure that the same user is only pulled once.
     *
     * @param string $id User ID in string form.
     * @return User
     * @throws Exception
     * @since 1.0
     */
    public static function getInstance($id = null)
    {
        if($id === null) {
            if(self::$lastUserId !== 0) $id = self::$lastUserId;
        }
        if(!array_key_exists($id, self::$instance)) {
            self::$instance[$id] = self::getUser($id);
        }

        self::$lastUserId = $id;

        return self::$instance[$id];
    }

    /**
     * This will take all changes made and save them to the Keygen server.
     * If there is an issue with the response other than successful it will throw an Exception with the reason.
     *
     * @return $this
     * @throws Exception
     * @since 1.0
     */
    public function save()
    {
        $changes =  parent::save();

        if(count($changes) > 0) {
            $changes['data']['type'] = 'users';
            $results = json_decode(self::getConnection()
                ->setURI($this->_values['id'])
                ->setPostParams(json_encode($changes))
                ->patch());
            if(!isset($results->data)) {
                throw new Exception($results->errors->detail);
            }
        }

        return $this;
    }
}
