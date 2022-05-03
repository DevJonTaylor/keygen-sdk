<?php
namespace Keygen\cURL;

use Exception;
use Keygen\cURL\Traits\OptionsTrait;

class CurlAbstract implements CurlInterface
{
    use OptionsTrait;

    /**
     * Runs the gathered requests and returns the response.
     *
     * @return string
     *
     * @since 2.0
     *
     * @throws Exception
     */
    public function run()
    {
        $curl = curl_init();
        curl_setopt_array($curl, $this->getOptions());

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if($error !== null && $error !== '')
            throw new Exception($error);

        return $response;
    }

    /**
     * Can provide the URL here or you can just run the method.
     *
     * @param null|string $url
     *
     * @return string
     *
     * @since 2.0
     *
     * @throws Exception
     */
    public function get($url = null)
    {
        if($url !== null)
            $this->setUrl($url);

        return $this
            ->setOption(CURLOPT_CUSTOMREQUEST, 'GET')
            ->run();
    }

    /**
     * Takes url if provided.
     * Creates a cURL post request and returns the response.
     *
     * @param null $url
     *
     * @return string
     *
     * @since 2.0
     *
     * @throws Exception
     */
    public function post($url = null)
    {
        if($url !== null) $this->setUrl($url);

        return $this
            ->setOption(CURLOPT_CUSTOMREQUEST, 'POST')
            ->run();
    }
}