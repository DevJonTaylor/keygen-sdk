<?php
namespace Keygen\Policy\Traits;

use Exception;
use Keygen\Policy\Policy;

trait SearchPoliciesTrait
{
    protected static $searchResults = array();
    protected static $currentSearchPage = 1;

    /**
     * Iterates through the entire collection of policies and searches and creates a list of Policy Objects based
     * on the request you provided.
     *
     * @param string $name Takes a recursive names separated by a delimiter '.'
     *                      I.E. attributes.metadata.policyCollection
     * @param string|int|boolean $value The value of the column found.
     * @throws Exception
     * @return array|Policy
     * @since 1.0
     */
    public static function search($name, $value = null)
    {
        $list = Policy::getListOfPolicies(self::$currentSearchPage);
        $searchNextPage = false;
        if(count($list) === 100) {
            $searchNextPage = true;
        }
        foreach($list as $index => $policy) {
            $found = false;
            if($value === null) {
                if(is_array($name)) {
                    foreach($name as $k => $v) {
                        $valueCheck = $policy->{$k};
                        if($valueCheck === null) {

                        } elseif(strpos(strtolower($valueCheck), strtolower($v)) !== false) {
                            $found = true;
                            break;
                        }
                    }
                } else {
                    if(isset($policy->{$name}) !== false) $found = true;
                }
            } else {
                $checkValue = $policy->{$name};
                if($checkValue === null) {
                    $found = false;
                } elseif(strpos(strtolower($checkValue), strtolower($value)) !== false) $found = true;
            }

            if($found === true) {
                self::$searchResults[] = $policy;
                self::$instance[$policy->id] = $policy;
            } else {
                unset($list[$index]);
                unset($policy);
            }
        }

        if($searchNextPage === true) {
            sleep(1);
            self::$currentSearchPage += 1;
            return self::search($name, $value);
        }

        self::$currentSearchPage = 1;

        switch(count(self::$searchResults)) {
            case 0:
                return null;
                break;
            case 1:
                return self::$searchResults[0];
                break;
            default:
                return self::$searchResults;
                break;
        }
    }
}