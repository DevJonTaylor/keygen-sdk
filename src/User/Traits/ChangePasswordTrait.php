<?php
namespace Keygen\User\Traits;

use Exception;
use Keygen\cURL\Connection;
use Keygen\User\User;
use Keygen\Util\Debug;
use Keygen\Util\Settings;

trait ChangePasswordTrait
{
    /**
     * @param string $email
     * @param boolean $deliver
     * @throws Exception
     * @since 2.0
     */
    public static function forgotPasswordRequest($email, $deliver)
    {
        $json = array(
            'meta' => array(
                'email' => $email,
                'deliver' => $deliver
            )
        );

        $connection = new Connection();
        $connection->setURI('passwords')
            ->setBearer(Settings::get('bearer'))
            ->setHeader(
                'Accept: application/vnd.api+json',
                'Content-Type: application/json',
                'cache-control: no-cache')
            ->setPostParams(json_encode($json))
            ->post();
    }


    public static function forgotPasswordReset($keygenUserId, $resetToken, $newPassword)
    {
        $json = array(
            'meta' => array(
                'passwordResetToken' => $resetToken,
                'newPassword' => $newPassword
            )
        );

        $connection = new Connection();
        $results = $connection->setURI('users', $keygenUserId, 'actions', 'reset-password')
            ->setBearer(Settings::get('bearer'))
            ->setHeader(
                'Accept: application/vnd.api+json',
                'Content-Type: application/json',
                'cache-control: no-cache')
            ->setPostParams(json_encode($json))
            ->post();
    }

    /**
     * This is performed in two calls to the KeygenTest API.
     * First call is to create the Bearer Token for the user.
     * Second call is to update hte password using hte Bearer Token.
     *
     * @param string $oldPassword The current password in use.
     * @param string $newPassword The new desired password.
     * @param string|null $bearer
     * @throws Exception
     * @since 1.0
     */
    public function changePassword($oldPassword, $newPassword, $bearer = null)
    {
        $token = $bearer === null ? User::basicAuthentication($this->{'email'}, $oldPassword)->Token : $bearer;
        $post = json_encode(array(
            'meta' => array(
                'oldPassword' => $oldPassword,
                'newPassword' => $newPassword
            )
        ));

        $results = json_decode(
            User::getConnection(false)
                ->setBearer($token)
                ->setURI($this->{'id'}, 'actions', 'update-password')
                ->setPostParams($post)
                ->post()
        );

        if(!isset($results->data)) {
            Debug::displayVar($results);
        } else {
            $this->sortingData($results);
        }
    }
}
