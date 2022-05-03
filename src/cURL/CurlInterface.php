<?php
namespace Keygen\cURL;

interface CurlInterface
{
    /**
     * Sets the param as a header before making the request to the site.
     *
     * @param mixed ...$headers
     *
     * @return $this
     *
     * @since version
     */
    public function setHeader(...$headers);

    /**
     * Gets the list of headers set.
     *
     * @return array
     *
     * @since 2.0
     */
    public function getHeader();

    /**
     * Sets the name of the option to the value provided.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this for chaining purposes.
     *
     * @since 2.0
     */
    public function setOption($name, $value);

    /**
     * It will search for the requested option.
     * If it is not located it will return the default
     * If it is found it will return that value.
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed|string
     *
     * @since 2.0
     */
    public function getOption($name, $default = '');

    /**
     * It will set all of the options at once.
     * Be warned this will overwrite any currently set options.
     * If the options provided are not in the form of an array it will reset the options to default.
     *
     * Default Options
     *  array(
     *       CURLOPT_RETURNTRANSFER => true,
     *       CURLOPT_ENCODING => '',
     *       CURLOPT_MAXREDIRS => 10,
     *       CURLOPT_TIMEOUT => 30,
     *       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     *       CURLOPT_HTTPHEADER => array(
     *           'Cache-Control: no-cache'
     *       )
     *  );
     *
     * @param array|null $options
     *
     * @return $this for chaining purposes.
     *
     * @since 2.0
     */
    public function setOptions($options = null);

    /**
     * This will return the currently set options.
     *
     * @return array
     *
     * @since version
     */
    public function getOptions();

    /**
     * Takes a URL to make the request to and sets it to the options.
     *
     * @param string $url
     *
     * @return $this for chaining purposes.
     *
     * @since 2.0
     */
    public function setUrl($url);

    /**
     * Returns the currently set URL.
     *
     * @return string
     *
     * @since 2.0
     */
    public function getUrl();

    /**
     * Takes the parameters to set for POST requests.
     *
     * @param $params
     *
     * @return $this for chaining purposes.
     *
     * @since 2.0
     */
    public function setPostParams($params);

    /**
     * Returns an empty string if the POST parameters has not used.
     * Returns the POST parameters to be used during a POST Request.
     *
     * @return string
     *
     * @since 2.0
     */
    public function getPostParams();

    /**
     * Runs the gathered requests and returns the response.
     *
     * @return string
     *
     * @since 2.0
     *
     * @throws \Exception
     */
    public function run();

    /**
     * Can provide the URL here or you can just run the method.
     *
     * @param null|string $url
     *
     * @return string
     *
     * @since 2.0
     *
     * @throws \Exception
     */
    public function get($url = null);

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
     * @throws \Exception
     */
    public function post($url = null);
}