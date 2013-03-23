<?php 

namespace JsonRpcLib\Server\Output\Data;

class Memory implements DataInterface {
    
    /**
     *
     * @var string
     */
    public $data = null;
    
    /**
     * 
     * @param string $data
     */
    public function write($data) {
        $this->data = $data;
    }
}
