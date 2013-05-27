<?php

namespace JsonRpcLib\Client\Adapter;

class Http implements AdapterInterface
{
    private $endPointUrl = null;

    private $params = array(
        'header' => "Content-Type: application/json\r\nConnection: Close\r\n",
        'method' => 'POST',
        'timeout' => 5
    );

    public function __construct($endPointUrl, array $params = array())
    {
        if (false === filter_var($endPointUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Not a valid URL ({$endPointUrl})");
        }

        $this->endPointUrl = $endPointUrl;
        $this->params = array_merge($this->params, $params);
    }

    public function send($content)
    {
        $options = $this->params;

        $options['content'] = $content;

        $context = stream_context_create(array('http' => $options));

        $response = file_get_contents($this->endPoint, false, $context);

        if (false === $response) {
            throw new \Exception('Connection to "'.$this->endPoint.'" failed');
        }

        return $response;
    }
}
