<?php 

namespace JsonRpcLib\Server\Input\Data;

class Input implements DataInterface {
    
    /**
     * @return string
     */
    public function read() {
        return file_get_contents('php://input');
    }
}
