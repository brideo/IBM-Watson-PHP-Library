<?php

namespace Brideo\IbmWatson\Ibm\ConceptInsights;

use Brideo\IbmWatson\Ibm\ConceptInsights;
use Brideo\IbmWatson\Ibm\Config\ConfigInterface;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Corpus extends ConceptInsights
{

    const CORPORA_URI = 'v2/corpora/';

    /**
     * @var mixed
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $method;

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
        if (!$name) {
            $name = strval(rand(1, 999999));
        }

        $this->name = $name;
        $this->method = $method;

        parent::__construct($config, $client, $accountId);
    }

    /**
     * Get the response of the configured Corpus.
     *
     * @return mixed|ResponseInterface
     */
    public function get()
    {
        return $this->request(
            $this->getCorpusUri(),
            $this->method,
            $this->options
        );
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
     * Create a new corpus
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
     * Retrieve the corpus.
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
     * Delete the corpus.
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
     * Update the corpus.
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
     * Set the contents of the request.
     *
     * @param $content
     */
    public function setBody($content)
    {
        $this->options['contents'] = $content;
    }

    /**
     * @return string
     */
    public function getCorpusUri()
    {
        return static::CORPORA_URI . $this->getAccountId() . '/' . $this->name;
    }

}
