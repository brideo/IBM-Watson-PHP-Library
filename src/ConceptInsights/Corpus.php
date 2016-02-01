<?php

namespace Brideo\IbmWatson\Ibm\ConceptInsights;

use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use Brideo\IbmWatson\Ibm\ConceptInsights;
use Brideo\IbmWatson\Ibm\Factory\ConceptInsights\CorpusFactory;
use Brideo\IbmWatson\Ibm\Factory\ConceptInsights\DocumentFactory;
use GuzzleHttp\ClientInterface;

class Corpus extends ConceptInsights
{

    const CORPORA_URI = 'v2/corpora/';

    protected $documents = [];

    /**
     * Corpus constructor.
     *
     * @param bool|string                 $name
     * @param ClientInterface|null|string $method
     * @param ConfigInterface|null        $config
     * @param ClientInterface|null        $client
     * @param bool|string                 $accountId
     */
    public function __construct(
        $name = false,
        $method = self::DEFAULT_REQUEST_METHOD,
        ConfigInterface $config = null,
        ClientInterface $client = null,
        $accountId = false
    ) {
        $this->name = $name;
        $this->method = $method;

        parent::__construct($config, $client, $accountId);
    }

    /**
     * @param $method
     *
     * @return $this
     */
    public function createNewInstance($method)
    {
        return CorpusFactory::create(
            $this->name,
            $method,
            $this->config,
            $this->getClient(),
            $this->accountId
        );
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $suffix = '';
        if ($this->name) {
            $suffix .= '/' . $this->name;
        }

        return static::CORPORA_URI . $this->getAccountId() . $suffix;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if ($this->method == 'DELETE' || $this->method == 'GET') {
            return true;
        }

        if (!$this->config->getConfig('body')) {
            $this->config->setConfig('body', '{}');
        }

        return true;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function createDocument($name)
    {
        $this->documents[$name] = DocumentFactory::create($this, $this->config, $name);

        return $this;
    }

    /**
     * @param $name
     *
     * @return null|Document
     */
    public function getDocument($name)
    {
        if (!isset($this->documents[$name])) {
            return $this->documents[$name];
        }

        return null;
    }

}
