<?php
namespace Keygen\License\Traits;


use Keygen\License\License;

trait SuspendTrait
{
    /**
     * Either suspend or unsuspend a license.
     *
     * @param boolean $suspend If true is provided it will suspend the license.  If false it will activate the license.
     * @return bool
     * @throws \Exception
     * @since 1.0
     */
    public function suspend($suspend)
    {
        $action = $suspend === true ? 'suspend' : 'reinstate';
        $results = json_decode(License::getConnection()
            ->setURI($this->id, 'actions', $action)
            ->post());
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
