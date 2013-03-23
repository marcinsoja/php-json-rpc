<?php 

namespace JsonRpcLib\Server;

class Server {

    /**
     * 
     * @param \JsonRpcLib\Server\Input\Message $input
     * @param \JsonRpcLib\Server\Output\Message $output
     */
    public function dispatch(
        \JsonRpcLib\Server\Input\Message $input, 
        \JsonRpcLib\Server\Output\Message $output) {
        
        try {
            $requests = $input->getIterator();
            
            foreach($requests as $request) {
                try {
                    $response = $this->processRequest($request);
                    $output->addResponse($response);
                    
                } catch (Exception $e) {

                    $response = new Output\Response();
                    $response->id = $request->id;
                    $response->error = array(
                        'code'      => $e->getCode(),
                        'message'   => $e->getMessage()
                    );

                    $output->addResponse($response);
                }
            }
            
        } catch (Exception $e) {
            $response = new Output\Response();
            $response->error = array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage()
            );

            $output->addResponse($response);
        }
        
        $output->write();
    }
    
    /**
     * 
     * @param \JsonRpcLib\Server\Input\Request $request
     * @return \JsonRpcLib\Server\Output\Response
     */
    private function processRequest(Input\Request $request) {
        
        $request->valid();
        
        // @todo process
        
        $response = new Output\Response();
        
        if(false == $request->isNotification()) {
            $response->id = $request->id;
            $response->result = 'result';
        }
        
        return $response;
    }
}
