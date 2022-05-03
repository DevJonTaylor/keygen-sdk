<?php
/**
 * User: Jon
 * Date: 2/26/2019
 */

namespace Keygen\cURL;

use Exception;
use Keygen\cURL\Traits\BuildUrlTrait;
use Keygen\Util\Debug;
use Keygen\Util\Settings;

class Connection extends CurlAbstract
{
    use BuildUrlTrait;

    const BASE_URL = 'api.keygen.sh';
    const BASE_URI = array('v1', 'actual');

    /**
     * Connection constructor.
     * @throws Exception
     * @since 1.0
     */
    public function __construct()
    {
        $this->setBase(self::BASE_URL)
            ->setURI(...self::BASE_URI)
            ->setHeader(
            'Accept: application/vnd.api+json',
            'Content-Type: application/json');
        $slug = Settings::get('slug');
        if($slug !== null) {
            $this->setURI($slug);
        }
    }

    /**
     * Overriding the method to allow for the URL Builder.
     *
     * @param null $url
     * @return string
     * @throws Exception
     * @since 1.0
     */
    public function post($url = null)
    {
        $url = $url === null ? $this->buildURL() : $url;
        return parent::post($url);
    }

    /**
     * Overriding the method to allow for the URL Builder.
     *
     * @param null $url
     * @return string
     * @throws Exception
     * @since 1.0
     */
    public function get($url = null)
    {
        $url = $url === null ? $this->buildURL() : $url;
        return parent::get($url);
    }

    /**
     * Performs a cURL using the PATCH method.  If a URL is provided then it will set that URL.
     * If not included then it will use the buildURL method to provide a URL to go to.
     *
     *
     * @param null|string $url where to connect to.
     * @return string is the results
     * @throws Exception
     * @since 1.0
     */
    public function patch($url = null)
    {
        $this->setUrl($url === null ? $this->buildURL() : $url);

        return $this
            ->setOption(CURLOPT_CUSTOMREQUEST, 'PATCH')
            ->run();
    }

    /**
     * Performs a cURL using the PUT method.  If a URL is provided then it will set that URL.
     * If not included then it will use the buildURL method to provide a URL to go to.
     *
     *
     * @param null|string $url where to connect to.
     * @return string is the results
     * @throws Exception
     * @since 1.0
     */
    public function put($url = null)
    {
        $this->setUrl($url === null ? $this->buildURL() : $url);

        return $this
            ->setOption(CURLOPT_CUSTOMREQUEST, 'PUT')
            ->run();
    }

    /**
     * Performs a cURL using the DELETE method.  If a URL is provided then it will set that URL.
     * If not included then it will use the buildURL method to provide a URL to go to.
     *
     *
     * @param null|string $url where to connect to.
     * @return string is the results
     * @throws Exception
     * @since 1.0
     */
    public function delete($url = null)
    {
        $this->setUrl($url === null ? $this->buildURL() : $url);

        return $this
            ->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE')
            ->run();
    }

    /**
     * Sets an Authorization Bearer Token into the headers.
     *
     * @param string $token The Bearer Token.
     * @return $this for chaining purposes.
     * @since 1.0
     */
    public function setBearer($token)
    {
        $this->setHeader("Authorization: Bearer {$token}");
        return $this;
    }
}