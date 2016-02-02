<?php

namespace Brideo\IbmWatson\Ibm\ConceptInsights;

use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use Brideo\IbmWatson\Ibm\ConceptInsights;
use Brideo\IbmWatson\Ibm\Factory\ConceptInsights\DocumentFactory;

class Document extends ConceptInsights
{

    /**
     * @var Corpus
     */
    protected $corpus;

    /**
     * @var bool|string
     */
    protected $name;

    /**
     * Document constructor.
     *
     * @param Corpus          $corpus
     * @param ConfigInterface $config
     * @param bool            $name
     * @param bool|string     $method
     */
    public function __construct(
        Corpus $corpus,
        ConfigInterface $config,
        $name = false,
        $method = self::DEFAULT_REQUEST_METHOD
    ) {
        $this->config = $config;
        $this->corpus = $corpus;
        $this->name = $name;
        $this->method = $method;

        parent::__construct(
            $this->config,
            $this->corpus->getClient(),
            $this->corpus->getAccountId()
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
    public function createNewInstance($method)
    {
        return DocumentFactory::create($this->corpus, $this->config, $this->name, $method);
    }

    /**
     * Any validation methods needed.
     *
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * Get the current uri.
     *
     * @return string
     */
    public function getUri()
    {
        $suffix = '/documents';
        if ($this->getName()) {
            $suffix .= '/'. $this->getName();
        }

        return $this->corpus->getUri() .$suffix;
    }

    /**
     * @param Corpus $corpus
     */
    public function setCorpus(Corpus $corpus)
    {
        $this->corpus = $corpus;
    }

    /**
     * @return bool|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}
