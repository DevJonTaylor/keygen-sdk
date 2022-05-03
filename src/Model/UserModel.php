<?php
namespace Keygen\Model;


class UserModel extends BaseObject
{

    const VARIABLE_PATH = array(
        'data' => null,
        'type' => 'data',
        'id' => 'data',
        'firstName' => 'data.attributes',
        'lastName' => 'data.attributes',
        'email' => 'data.attributes',
        'role' => 'data.attributes',
        'metadata' => 'data.attributes'
    );


    const READ_WRITE = array(
        'id' => false,
        'type' => false,
        'fullName' => false,
        'firstName' => true,
        'lastName' => true,
        'email' => true,
        'role' => false,
        'created' => false,
        'updated' => false,
        'metadata' => true);

    public function __construct($data = null)
    {
        $options = array('data' => $data);

        parent::__construct($options);
    }
}
