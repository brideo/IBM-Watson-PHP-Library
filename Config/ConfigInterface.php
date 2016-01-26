<?php

namespace Brideo\IbmWatson\Ibm\Config;

interface ConfigInterface
{

    /**
     * Return an array of the username and
     * password with an 'auth' key.
     *
     * @return array
     */
    public function getAuth();

    /**
 * Set the username.
 *
 * @param string $username
 *
 * @return $this
 */
    public function setUsername($username);

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Set the password.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password);

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword();

    /**
     * Set the base uri.
     *
     * @param $baseUri
     *
     * @return $this
     */
    public function setBaseUri($baseUri);

    /**
     * Get the base uri.
     *
     * @return string
     */
    public function getBaseUri();

    /**
     * Set an item in the config.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function setConfig($key, $value);

    /**
     * Get the current config.
     *
     * @param null $key
     *
     * @return array
     */
    public function getConfig($key = null);

}
