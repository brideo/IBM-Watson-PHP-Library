<?php

namespace App\Services\Ibm\Config;

class Config implements ConfigInterface
{

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Config constructor.
     *
     * @param null  $baseUrl
     * @param null  $username
     * @param null  $password
     * @param array $config
     */
    public function __construct(
        $baseUrl = null,
        $username = null,
        $password = null,
        $config = []
    ) {
        $this->setUsername($username);
        $this->setPassword($username);
        $this->setBaseUri($baseUrl);

        foreach ($config as $key => $value) {
            $this->setConfig($key, $value);
        }
    }

    /**
     * Return an array of the username and
     * password with an 'auth' key.
     *
     * @return array
     */
    public function getAuth()
    {
        return [
            'auth' => [
                $this->getUsername(),
                $this->getPassword()
            ]
        ];
    }

    /**
     * Set the username.
     *
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the password.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the base uri.
     *
     * @param $baseUri
     *
     * @return $this
     */
    public function setBaseUri($baseUri)
    {
        $this->setConfig('base_uri', $baseUri);

        return $this;
    }

    /**
     * Get the base uri.
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->getConfig('base_uri');
    }

    /**
     * Set an item in the config.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Get the current config.
     *
     * @param null $key
     *
     * @return array|mixed
     */
    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        } elseif (!isset($this->config[$key])) {
            return null;
        }

        return $this->config[$key];
    }
}
