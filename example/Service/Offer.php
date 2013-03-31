<?php

namespace Example\Service;

class Offer
{
    public function get($id)
    {
        return array('id'=>$id, 'name'=>'Sample offer');
    }
    private function sum($a, $b)
    {
        return $a + $b;
    }
}
