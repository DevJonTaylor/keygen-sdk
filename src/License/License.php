<?php
namespace Keygen\License;

use Keygen\License\Traits\CreateLicenseTrait;
use Keygen\License\Traits\GetLicenseTrait;
use Keygen\License\Traits\LicenseConnectionTrait;
use Keygen\License\Traits\PoliciesTrait;
use Keygen\License\Traits\RevokeTrait;
use Keygen\License\Traits\SuspendTrait;
use Keygen\Model\LicenseModel;
use Exception;
use Keygen\Util\Debug;
use stdClass;

/**
 * @property      string    name
 * @property-read boolean   concurrent
 * @property-read string    created
 * @property      boolean   encrypted
 * @property      string    expiry
 * @property-read boolean   floating
 * @property      string    id
 * @property      string    key
 * @property-read string    lastCheckIn
 * @property-read int       maxMachines
 * @property-read int       maxUses
 * @property      boolean   protected
 * @property      string    nextCheckIn
 * @property-read boolean   requireCheckIn
 * @property      string    scheme
 * @property      string    self
 * @property-read boolean   strict
 * @property      boolean   suspended
 * @property      string    type
 * @property-read string    updated
 * @property      integer   uses
 * @since 1.0
 */
class License extends LicenseModel
{
    use LicenseConnectionTrait,
        GetLicenseTrait,
        CreateLicenseTrait,
        PoliciesTrait,
        SuspendTrait,
        RevokeTrait;

    protected static $instance = array();

    /**
     * Ensures that only one License Object itself can be called at a time.
     *
     * @param string $id The License ID
     * @return License
     * @throws Exception if there is an issue with the request itself.
     * @since 1.0
     */
    public static function getInstance($id)
    {
        if(!array_key_exists($id, self::$instance)) {
            self::$instance[$id] = self::getLicense($id);
        }

        return self::$instance[$id];
    }

    /**
     * Takes the current changes and updates it to the Keygen.sh API.
     *
     * @return $this for chaining purposes.
     * @throws Exception
     * @since 1.0
     */
    public function save()
    {
        $changes = parent::save();
        $json = new stdClass;
        $json->data = new stdClass;
        $json->data->type = 'license';

        if (count($changes) > 0) {
            if(array_key_exists('data', $changes)) {
                foreach($changes['data'] as $key => $val) {
                    if($key === 'type') continue;
                    $json->data->{$key} = $val;
                }
            }
            elseif(array_key_exists('attributes', $changes)) {
                foreach($changes as $key => $val) {
                    $json->data->{$key} = $val;
                }
            } else {
                $json->data->attributes = $changes;
            }
            $results = json_decode(self::getConnection()
                ->setURI($this->_values['id'])
                ->setPostParams(json_encode($json))
                ->patch());

            if(isset($results->data) === true)
                return $this;

            Debug::displayVar($results, $json, $changes);

            $title = $results->errors[0]->title;
            $detail = $results->errors[0]->detail;
            $pointer = $results->errors[0]->source->pointer;
            throw new Exception(ucfirst("{$title} {$detail} {$pointer}"));
        }

        return $this;
    }

}
