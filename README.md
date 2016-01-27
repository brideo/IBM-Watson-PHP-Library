#IBM Watson PHP Library

A Library which uses PHP to make communicating with the IBM Watson library easier.

This is still in development, feedback is welcome.
 
##Speech To Text IBM PHP
 
 This API is complete, however it hasn't been heavily tested and I am yet to write and PHP Unit tests for it, please feel free to submit a pull request if you would like to write any tests or improve the class in anyway.
 
###How to use
 
 To instantiate:
 
    $config = new Config();
    $config->setUsername('IBM_SPEECH_TO_TEXT_USERNAME');
    $config->setPassword'IBM_SPEECH_TO_TEXT_PASSWORD');

    $speechToText = new SpeechToText($config);
 
 
 The whole IBM Library uses Guzzle, to set options you can use `$config->setConfig($key, $value);` You can also pass an array of items like shown below:
 
    $params = ['headers' => 'X-Brideo']
    $speechToText->config->setConfig($params)

There is a helper `request()` method which wraps the Guzzle Client request method, however the parameters are in a slightly different order so you may want to check that.

To recognize a file:

    $filePath = 'example.ogg';
    $speechToText->recognize($filePath, 'audio/ogg')

To get the transcripts of the last recognized file you can run `$speechToText->getTranscripts()`

Other commands are:

* `observeResult()`
* `recognizeStatus()`
  
##Concept Insights

This is still in development, however you can currently create, delete, update and retrieve Corpus and Document.

    $config = new Config();
    $config->setUsername('username');
    $config->setPassword('password');
    
    $corpus = CorpusFactory::create('testCorpusBrideo', Corpus::DEFAULT_REQUEST_METHOD, $config);
    $corpus->delete();
    $corpus->create();
    
    
    $document = DocumentFactory::create($corpus, $config, 'testDocumentBrideo');
    $document->create();

#Todos

* Write PHP Unit Tests
* Add Documents to corpus
