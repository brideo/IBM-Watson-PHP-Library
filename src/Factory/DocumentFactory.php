<?php

namespace Brideo\IbmWatson\Ibm\Factory\ConceptInsights;

use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use Brideo\IbmWatson\Ibm\ConceptInsights\Corpus;
use Brideo\IbmWatson\Ibm\ConceptInsights\Document;

class DocumentFactory
{

    /**
     * @param Corpus|null     $corpus
     * @param ConfigInterface $config
     * @param bool            $name
     *
     * @param string          $method
     *
     * @return Document
     */
    public static function create(
        Corpus $corpus = null,
        ConfigInterface $config,
        $name = false,
        $method = Document::DEFAULT_REQUEST_METHOD
    )
    {
        return new Document($corpus, $config, $name, $method);
    }
}
