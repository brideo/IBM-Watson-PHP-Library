<?php

namespace Brideo\IbmWatson\Ibm;

use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Ibm
{

    const IBM_URI = null;
    const IBM_SESSION_URI = 'v1/sessions';
    const IBM_MODEL_URI = 'v1/models';

    const DEFAULT_REQUEST_METHOD = 'POST';

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var mixed
     */
    protected $models;

    /**
     * @var array
     */
    protected $model = [];

    /**
     * @var array
     */
    protected $clientConfig = [];

    /**
     * @var mixed|ResponseInterface
     */
    protected $session;

    /**
     * @var ConfigInterface
     */
    public $config;

    /**
     * Ibm constructor.
     *
     * @param ConfigInterface      $config
     * @param ClientInterface|null $client
     */
    public function __construct(
        ConfigInterface $config = null,
        ClientInterface $client = null
    ) {
        $this->config = $config ?: new Config();
        $this->client = $client ?: new Client($this->config->getConfig());
    }

    /**
     * Get the client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * A helper method for creating requests.
     *
     * @param null   $uri
     * @param string $method
     * @param array  $options
     * @param bool   $withAuth
     *
     * @return mixed|ResponseInterface
     */
    public function request(
        $uri = null,
        $method = self::DEFAULT_REQUEST_METHOD,
        array $options = [],
        $withAuth = true
    ) {
        if ($withAuth) {
            $options = array_merge($options, $this->config->getAuth());
        }

        return $this->getClient()->request($method, $uri, $options);
    }

    /**
     * A helper method for creating async requests.
     *
     * @param null   $uri
     * @param string $method
     * @param array  $options
     * @param bool   $withAuth
     *
     * @return mixed|ResponseInterface
     */
    public function requestAsync(
        $uri = null,
        $method = self::DEFAULT_REQUEST_METHOD,
        $options = [],
        $withAuth = true
    ) {
        if ($withAuth) {
            $options = array_merge($options, $this->config->getAuth());
        }

        return $this->getClient()->requestAsync($method, $uri, $options);
    }

    /**
     * @return mixed|ResponseInterface
     */
    public function getSession()
    {
        if (!$this->session) {
            $this->session = $this->request(static::IBM_SESSION_URI);
        }

        return $this->session;
    }

    /**
     * Return a session id.
     *
     * @return string
     */
    public function getSessionId()
    {
        if (!$this->sessionId) {
            $contents = json_decode($this->getSession()->getBody()->getContents());
            $this->sessionId = $contents->session_id;
        }

        return $this->sessionId;
    }

    /**
     * Delete the current session.
     *
     * @return $this
     */
    public function deleteSession()
    {
        $this->request(static::IBM_SESSION_URI . '/' . $this->getSessionId(), 'DELETE');

        return $this;
    }

    /**
     * Get a specific model
     *
     * @param $name
     *
     * @return mixed|ResponseInterface
     */
    public function getModel($name)
    {
        if (!isset($this->model[$name])) {
            $this->model[$name] = $this->request(static::IBM_MODEL_URI . '/' . $name, 'GET');
        }

        return $this->model[$name];
    }

    /**
     * Get all models.
     *
     * @return mixed|ResponseInterface
     */
    public function getModels()
    {
        if (!$this->models) {
            $this->models = $this->request(static::IBM_MODEL_URI, 'GET');
        }

        return $this->models;
    }

    /**
     * Return the uri with the session.
     *
     * @param $uri
     *
     * @return string
     */
    public function getUrlWithSession($uri)
    {
        return static::IBM_SESSION_URI . '/' . $this->getSessionId() . $uri;
    }

}
