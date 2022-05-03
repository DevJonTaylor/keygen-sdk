<?php
namespace Keygen\Model;


class LicenseModel extends BaseObject
{
    const READ_WRITE = array(
        'id' => false,
        'type' => false,
        'name' => true,
        'key' => false,
        'expiry' => true,
        'uses' => false,
        'protected' => true,
        'suspended' => true,
        'scheme' => false,
        'encrypted' => false,
        'floating' => false,
        'concurrent' => false,
        'strict' => false,
        'maxMachines' => false,
        'maxUses' => false,
        'requireCheckIn' => false,
        'lastCheckIn' => false,
        'nextCheckIn' => false,
        'metadata' => true,
        'created' => false,
        'updated' => false);

    public function __construct($data = null)
    {
        $options = array('data' => $data);

        parent::__construct($options);
    }
}