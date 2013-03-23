<?php

namespace JsonRpcLib\Server\Service\Provider;

interface ProviderInterface
{
    public function execute($name, array $params);
}
