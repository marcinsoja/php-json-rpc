<?php

namespace JsonRpcLib\Client\Adapter;

interface AdapterInterface
{
    public function send($request);
}
