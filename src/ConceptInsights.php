<?php

namespace Brideo\IbmWatson\Ibm;

use Brideo\IbmWatson\Ibm\Api\ConceptInsightsInterface;
use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;

abstract class ConceptInsights extends Ibm implements ConceptInsightsInterface
{

    const IBM_URI = 'https://gateway.watsonplatform.net/concept-insights/api/';
    const IMB_ACCOUNT_URI = 'v2/accounts';

    protected $accountId;

    protected $method;

    protected $name;

    /**
     * ConceptInsights constructor.
     *
     * @param ConfigInterface      $config
     * @param ClientInterface|null $client
     * @param bool                 $accountId
     */
    public function __construct(
        ConfigInterface $config = null,
        ClientInterface $client = null,
        $accountId = false
    ) {
        $this->config = $config ?: new Config();
        $this->config->setBaseUri(static::IBM_URI);

        $this->accountId = $accountId;

        parent::__construct($this->config, $client);
    }

    /**
     * Get the account id.
     *
     * @return mixed
     */
    public function getAccountId()
    {
        if (!$this->accountId) {
            $response = $this->request(static::IMB_ACCOUNT_URI, 'GET');
            $contents = json_decode($response->getBody()->getContents(), true);
            $this->accountId = $contents['accounts'][0]['account_id'];
        }

        return $this->accountId;
    }

    /**
     * Create a new object in IBM Concept Insights
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function create($name = false)
    {
        if ($name) {
            $this->name = $name;
        }

        return $this->createNewInstance('PUT')->get();
    }

    /**
     * Retrieve a new object in IBM Concept Insights.
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function retrieve($name = false)
    {
        if ($name) {
            $this->name = $name;
        }

        return $this->createNewInstance('GET')->get();
    }

    /**
     * Delete a new object in IBM Concept Insights.
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function delete($name = false)
    {
        if ($name) {
            $this->name = $name;
        }

        return $this->createNewInstance('DELETE')->get();
    }

    /**
     * Update a new object in IBM Concept Insights.
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function update($name = false)
    {
        if ($name) {
            $this->name = $name;
        }

        return $this->createNewInstance('POST')->get();
    }

    /**
     * Get the response of the configured Corpus.
     *
     * @return mixed|ResponseInterface
     * @throws Exception
     */
    public function get()
    {
        if (!$this->validate()) {
            throw new Exception("Something is wrong with the validation of the request.");
        }

        return $this->request(
            $this->getUri(),
            $this->method,
            $this->config->getConfig()
        );
    }

    /**
     * Create a new instance of self in most cases
     * you could actually replace this with any
     * class you choose, it would just have to
     * implement the `get()` method.
     *
     * @param $method
     *
     * @return $this|mixed
     */
    abstract public function createNewInstance($method);

    /**
     * Any validation methods needed.
     *
     * @return bool
     */
    abstract public function validate();

    /**
     * Get the current uri.
     *
     * @return string
     */
    abstract public function getUri();



}
