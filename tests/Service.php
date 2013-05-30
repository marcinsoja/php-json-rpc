<?php

use JsonRpcLib\Server\Service\Wrapper\Annotation\Service as RPC;

class Service
{
    /**
     *
     * @RPC
     */
    public function sum($a, $b)
    {
        return $a + $b;
    }
    /**
     *
     * @RPC
     */
    public function subtractNumbers($a, $b)
    {
        return $a - $b;
    }
    /**
     *
     * @RPC
     */
    public function update($a, $b, $c, $d, $e, $f = null)
    {
        return true;
    }
    /**
     *
     * @RPC
     */
    public function updateDB($a, $b = null)
    {
        return $a;
    }
    /**
     *
     * @RPC
     */
    public function lock()
    {
        return true;
    }
    /**
     *
     * @RPC
     */
    public function error()
    {
        throw new \Exception('Error');
    }
    private function privateFn($a)
    {
        return $a;
    }
    /**
     *
     * @RPC
     */
    public static function staticFn($a)
    {
        return $a;
    }
}
