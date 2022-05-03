<?php
namespace Keygen\Model;


class PolicyModel extends BaseObject
{

    const READ_WRITE = array(
        'id' => false,
        'type' => false,
        "name" => true,
        "duration" => true,
        "strict" => true,
        "floating" => true,
        "usePool" => false,
        "maxMachines" => true,
        "maxUses" => true,
        "concurrent" => true,
        "scheme" => false,
        "encrypted" => false,
        "protected" => true,
        "requireProductScope" => true,
        "requirePolicyScope" => true,
        "requireMachineScope" => true,
        "requireFingerprintScope" => true,
        "requireCheckIn" => true,
        "checkInInterval" => true,
        "checkInIntervalCount" => true,
        'metadata' => true);

    public function __construct($data = null)
    {
        $options = array('data' => $data);

        parent::__construct($options);
    }
}