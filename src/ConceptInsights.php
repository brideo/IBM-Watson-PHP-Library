<?php

namespace Brideo\IbmWatson\Ibm;

use Brideo\IbmWatson\Ibm\Config\Config;
use Brideo\IbmWatson\Ibm\Config\ConfigInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7;

class ConceptInsights extends Ibm
{

    const IBM_URI = 'https://gateway.watsonplatform.net/concept-insights/api/';
    const IMB_ACCOUNT_URI = 'v2/accounts';

    protected $accountId;

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

}
