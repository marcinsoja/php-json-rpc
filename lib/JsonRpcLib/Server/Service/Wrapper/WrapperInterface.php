<?php

namespace JsonRpcLib\Server\Service\Wrapper;

interface WrapperInterface
{
    public function execute($name, array $params);
}
