<?php

namespace JsonRpcLib\Server;

class Server
{
    /**
     *
     * @var Service\Manager\ManagerInterface
     */
    private $serviceManager = null;

    /**
     *
     * @param Service\Manager\ManagerInterface $manager
     */
    public function __construct(Service\Manager\ManagerInterface $manager)
    {
        $this->serviceManager = $manager;
    }

    /**
     *
     * @param  \JsonRpcLib\Server\Service\Wrapper\WrapperInterface|object $service
     * @param  string|null                                                $name
     * @return Server
     */
    public function addService($service, $name = null)
    {
        if ($service instanceof Service\Wrapper\WrapperInterface) {

        } elseif ($service instanceof \Closure) {
            $service = new Service\Wrapper\ClosureWrapper($service);
        } elseif (is_object($service)) {
            $service = new Service\Wrapper\ObjectWrapper($service);
        }

        if (false == $service instanceof Service\Wrapper\WrapperInterface) {
            throw new \InvalidArgumentException();
        }

        $this->serviceManager->addService($service, $name);

        return $this;
    }

    /**
     *
     * @param \JsonRpcLib\Server\Input\Message  $input
     * @param \JsonRpcLib\Server\Output\Message $output
     */
    public function dispatch(
        \JsonRpcLib\Server\Input\Message $input,
        \JsonRpcLib\Server\Output\Message $output) {

        try {
            $requests = $input->getIterator();

            foreach ($requests as $request) {
                try {
                    $response = $this->processRequest($request);
                    $output->addResponse($response);

                } catch (Exception $e) {

                    $response = new Output\Response();
                    $response->id = $request->id;
                    $response->error = new Output\Error($e->getMessage(), $e->getCode());

                    $output->addResponse($response);
                }
            }

        } catch (Exception $e) {

            $response = new Output\Response();
            $response->error = new Output\Error($e->getMessage(), $e->getCode());

            $output->addResponse($response);
        }

        $output->write();
    }

    /**
     *
     * @param  \JsonRpcLib\Server\Input\Request   $request
     * @return \JsonRpcLib\Server\Output\Response
     */
    private function processRequest(Input\Request $request)
    {
        $request->valid();

        try {

            $service = $this->serviceManager->getService($request->method);

            if (null == $service) {
                throw new \JsonRpcLib\Server\Exception(
                    \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND(),
                    \JsonRpcLib\Server\Output\Error::METHOD_NOT_FOUND
                );
            }

            $result = $this->serviceManager->execute(
                $service,
                $request->method,
                $request->getParams()
            );

        } catch (\Exception $e) {
            if ($e instanceof Exception) {
                throw $e;
            }
            throw new \JsonRpcLib\Server\Exception(
                Output\Error::SERVER_ERROR(),
                Output\Error::SERVER_ERROR,
                $e
            );
        }

        $response = new Output\Response();

        if (false == $request->isNotification()) {
            $response->id = $request->id;
            $response->result = $result;
        }

        return $response;
    }
}
