<?php

class MockService {
    
    public function sum($a, $b) {
        return $a + $b;
    }
    public function subtractNumbers($a, $b) {
        return $a - $b;
    }
    public function update($a, $b, $c, $d, $e, $f = null) {
        return true;
    }
    public function updateDB($a, $b = null) {
        return $a;
    }
    public function lock() {
        return true;
    }
    public function error() {
        throw new \Exception('Error');
    }
    private function privateFn($a) {
        return $a;
    }
    public static function staticFn($a) {
        return $a;
    }
}