<?php
namespace Keygen\Policy;

use Exception;
use Keygen\Model\PolicyModel;
use Keygen\Policy\Traits\GetPolicy;
use Keygen\Policy\Traits\ListPoliciesTrait;
use Keygen\Policy\Traits\PolicyConnectionTrait;
use Keygen\Policy\Traits\SearchPoliciesTrait;

class Policy extends PolicyModel
{
    use PolicyConnectionTrait,
        ListPoliciesTrait,
        SearchPoliciesTrait,
        GetPolicy;

    protected static $instance = array();

    /**
     * Ensures that only one Policy Object itself can be called at a time.
     *
     * @param string $id The Policy ID
     * @return Policy
     * @throws Exception if there is an issue with the request itself.
     * @since 1.0
     */
    public static function getInstance($id)
    {
        if(!array_key_exists($id, self::$instance)) {
            self::$instance[$id] = self::getPolicy($id);
        }

        return self::$instance[$id];
    }


}