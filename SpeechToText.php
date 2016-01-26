<?php

namespace App\Services\Ibm;

use App\Services\Ibm\Config\Config;
use App\Services\Ibm\Config\ConfigInterface;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\SetCookie;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7;

class SpeechToText extends Ibm
{

    const IBM_URI = 'https://stream.watsonplatform.net/speech-to-text/api/';

    const CONTENT_TYPE = 'audio/ogg';

    const IBM_RECOGNIZE_URI = 'v1/recognize';
    const IBM_OBSERVE_RESULT_URI = 'observe_result';

    /**
     * @var ResponseInterface
     */
    protected $recognize;

    /**
     * @var CookieJar
     */
    protected $cookieJar;

    /**
     * @var array
     */
    protected $setCookie = [];

    /**
     * @var array
     */
    protected $transcripts = [];

    /**
     * SpeechToText constructor.
     *
     * @param ConfigInterface|null    $config
     * @param ClientInterface|null    $client
     * @param CookieJarInterface|null $cookieJar
     */
    public function __construct(
        ConfigInterface $config = null,
        ClientInterface $client = null,
        CookieJarInterface $cookieJar = null
    ) {
        $this->config = $config ?: new Config();
        $this->config->setBaseUri(static::IBM_URI);

        $this->cookieJar = $cookieJar ?: new CookieJar();
        parent::__construct($this->config, $client);
    }

    /**
     * Returns only the final results. To enable interim results,
     * use session-based requests or Websockets API. Endianness
     * of the incoming audio is autodetected. Audio files larger
     * than 4 MB are required to be sent in streaming mode
     * (chunked transfer-encoding).
     *
     * Streaming audio size limit is 100 MB. In streaming mode,
     * the connection is closed by the server if no data chunk
     * is received for more than 30 seconds and the last chunk
     * has not been sent yet (if all data has been sent, it can
     * take more than 30 seconds to generate the response and the
     * request will not time out).
     *
     * Use getUrlWithSession() to prevent the session from expiring.
     * The connection is also closed by the server if no speech is
     * detected for "inactivity_timeout" seconds of audio (not
     * processing time). This time can be set by "inactivity_timeout"
     * parameter; the default is 30 seconds.
     *
     * @param string $pathToFile
     * @param string $contentType
     * @param bool   $uri
     *
     * @return $this
     */
    public function recognize($pathToFile, $contentType = self::CONTENT_TYPE, $uri = false)
    {
        $uri = $uri ?: $this->getUrlWithSession('/recognize');

        foreach ($this->getSetCookieHeader() as $cookie) {
            $cookie = SetCookie::fromString($cookie);
            $cookie->setDomain('stream.watsonplatform.net');
            $this->cookieJar->setCookie($cookie);
        }

        $data[] = [
            'name'     => 'audio_file',
            'contents' => fopen($pathToFile, 'r'),
        ];

        $this->recognize = $this->request($uri, 'POST', [
                'multipart' => $data,
                'cookies'   => $this->cookieJar,
                'headers'   => [
                    'content-type' => $contentType
                ],
            ]
        );

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getTranscripts()
    {
        if (count($this->transcripts)) {
            return $this->transcripts;
        }

        if (!$this->recognize) {
            Throw new Exception("You must run the `recognize` before calling for the transcript.");
        }

        $body = $this->recognize->getBody();
        $content = json_decode($body->read($body->getSize()), true);

        foreach ($content['results'] as $result) {
            foreach ($result['alternatives'] as $alternatives) {
                $this->transcripts[strval($alternatives['confidence'])] = $alternatives['transcript'];
            }
        }

        return $this->transcripts;
    }

    /**
     * Get the state of the engine to check whether the recognize
     * request is available. This is the way to check if the session
     * is ready to accept a new recognition task. The returned state
     * has to be 'initialized' to be able to do call recognize.
     *
     * @return mixed|ResponseInterface
     */
    public function recognizeStatus()
    {
        return $this->request($this->getUrlWithSession('/recognize'));
    }

    /**
     * Result observer for upcoming or ongoing recognition
     * task within the session.
     *
     * This method should be called before calling recognize
     * otherwise, the response for this method waits for
     * the next recognition request.
     *
     * @return mixed|ResponseInterface
     */
    public function observeResult()
    {
        return $this->request(static::IBM_OBSERVE_RESULT_URI, 'GET');
    }

    /**
     * Get the `Set-Cookie` header.
     *
     * @return array
     */
    public function getSetCookieHeader()
    {
        if (!$this->setCookie) {
            $headers = $this->getSession()->getHeaders();
            $this->setCookie = $headers['Set-Cookie'];
        }

        return $this->setCookie;
    }

}
