<?php

namespace Brideo\IbmWatson\Ibm\ConceptInsights;

use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use Brideo\IbmWatson\Ibm\ConceptInsights;
use Brideo\IbmWatson\Ibm\Config;
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
        $config = new Config(
            $this->config->getBaseUri(),
            $this->config->getUsername(),
            $this->config->getPassword()
        );

        $this->documents[$name] = DocumentFactory::create($this, $config, $name);

        return $this;
    }

    /**
     * @param $name
     *
     * @return null|Document
     */
    public function getDocument($name)
    {
        if (isset($this->documents[$name])) {
            return $this->documents[$name];
        }

        return null;
    }

    /**
     * Retrieve the documents within the Corpus.
     *
     * @param array $ids
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function getDocuments(array $ids)
    {
        return $this->request(
            $this->getUri() . '/documents',
            'GET',
            json_encode([
                'documents' => $ids
            ])
        );
    }

    /**
     * Get the related concepts.
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function getLabelSearch($search)
    {
        return $this->request(
            $this->getUri() . '/label_search?query=' . urlencode($search),
            'GET',
            $this->config->getConfig()
        );
    }

}
