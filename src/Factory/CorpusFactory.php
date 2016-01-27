<?php

namespace Brideo\IbmWatson\Ibm\Factory\ConceptInsights;

use Brideo\IbmWatson\Ibm\Api\ConfigInterface;
use Brideo\IbmWatson\Ibm\ConceptInsights\Corpus;
use GuzzleHttp\ClientInterface;

class CorpusFactory
{

    /**
     * @param bool                 $name
     * @param string               $method
     * @param ConfigInterface|null $config
     * @param ClientInterface|null $client
     * @param bool                 $accountId
     *
     * @return Corpus
     */
    public static function create(
        $name = false,
        $method = Corpus::DEFAULT_REQUEST_METHOD,
        ConfigInterface $config = null,
        ClientInterface $client = null,
        $accountId = false
    )
    {
        return new Corpus($name, $method, $config, $client, $accountId);
    }
}
