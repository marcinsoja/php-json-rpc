<?php 

namespace JsonRpcLib\Server\Input\Data;

interface DataInterface {
    
    /**
     * @return string
     */
    public function read();
}
