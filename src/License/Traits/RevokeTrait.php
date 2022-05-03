<?php
namespace Keygen\License\Traits;


use Exception;
use Keygen\License\License;

trait RevokeTrait
{
    /**
     * This will delete the license and it will no longer be usable.
     *
     * @return bool
     * @throws Exception
     * @since 1.0
     */
    public function revoke()
    {
        $results = json_decode(License::getConnection()
            ->setURI($this->id, 'actions', 'revoke')
            ->delete());
        if(!$results->data->errors) {
            return true;
        } else {
            $msg = array(
                $results->data->errors[0]->title,
                $results->data->errors[0]->code,
                $results->data->errors[0]->detail
            );
            throw new Exception(implode(':',$msg));
        }
    }
}
