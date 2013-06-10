<?php

namespace JsonRpcLib\Server\Service\Wrapper\Plugin;

use \JsonRpcLib\Server\Service\Wrapper\ExecuteEvent;

class ResultSignature
{
    private $privateKey = null;

    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function __invoke(ExecuteEvent $e)
    {
        $result = $e->getResult();

        $e->setResult(array(
            'result'=>$result,
            'signature'=>$this->getSignature($result)
            )
        );
    }

    private function getSignature($result)
    {
        return md5(serialize($result).$this->privateKey);
    }
}
