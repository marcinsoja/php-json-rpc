<?php 

namespace JsonRpcLib\Server\Input\Data;

class Memory implements DataInterface {
    
    /**
     *
     * @var string
     */
    private $data = null;
    
    /**
     * 
     * @param string $data
     */
    public function __construct($data) {
        $this->data = $data;
    }
    
    /**
     * 
     * @return string
     */
    public function read() {
        return $this->data;
    }
}
