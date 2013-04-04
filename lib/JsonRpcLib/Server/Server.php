<?php

namespace JsonRpcLib\Server;

use \JsonRpcLib\Server\Service\Wrapper\WrapperInterface;
use \JsonRpcLib\Server\Service\Wrapper\ClosureWrapper;
use \JsonRpcLib\Server\Service\Wrapper\ObjectWrapper;
use \JsonRpcLib\Server\Input\Message as InputMessage;
use \JsonRpcLib\Server\Output\Message as OutputMessage;
use \JsonRpcLib\Server\Input\Data\Input as DataInput;
use \JsonRpcLib\Server\Output\Data\Output as DataOutput;
use \JsonRpcLib\Server\Output\Error;
use \JsonRpcLib\Server\Exception;

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
    public function __construct(Service\Manager\ManagerInterface $manager = null)
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
        if (false == $service instanceof WrapperInterface) {
            if ($service instanceof \Closure) {
                $service = new ClosureWrapper($service);
            } elseif (is_object($service)) {
                $service = new ObjectWrapper($service);
            }
        }

        if (false == $service instanceof WrapperInterface) {
            throw new \InvalidArgumentException();
        }

        $this->getServiceManager()->addService($service, $name);

        return $this;
    }

    /**
     *
     * @param \JsonRpcLib\Server\Input\Message  $input
     * @param \JsonRpcLib\Server\Output\Message $output
     */
    public function dispatch(InputMessage $input, OutputMessage $output)
    {
        try {
            $requests = $input->getIterator();

            foreach ($requests as $request) {
                try {
                    $response = $this->processRequest($request);
                    $output->addResponse($response);

                } catch (Exception $e) {

                    $response = new Output\Response();
                    $response->id = $request->id;
                    $response->error = new Error($e->getMessage(), $e->getCode());

                    $output->addResponse($response);
                }
            }

        } catch (Exception $e) {

            $response = new Output\Response();
            $response->error = new Error($e->getMessage(), $e->getCode());

            $output->addResponse($response);
        }

        $output->write();
    }

    /**
     * @param  \JsonRpcLib\Server\Input\Message|null  $input
     * @param  \JsonRpcLib\Server\Output\Message|null $output
     * @return \JsonRpcLib\Server\Output\Data\Output
     */
    public function handle(InputMessage $input = null, OutputMessage $output = null)
    {
        if (null == $input) {
            $input = new InputMessage(new DataInput());
        }
        if (null == $output) {
            $output = new OutputMessage(new DataOutput());
        }

        $this->dispatch($input, $output);

        return $output;
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

            $service = $this->getServiceManager()->getService($request->method);

            if (null == $service) {
                throw new Exception(Error::METHOD_NOT_FOUND(), Error::METHOD_NOT_FOUND);
            }

            $result = $this->getServiceManager()->execute(
                $service,
                $request->method,
                $request->getParams()
            );

        } catch (\Exception $e) {
            if ($e instanceof Exception) {
                throw $e;
            }
            throw new Exception(Error::SERVER_ERROR(), Error::SERVER_ERROR, $e);
        }

        $response = new Output\Response();

        if (false == $request->isNotification()) {
            $response->id = $request->id;
            $response->result = $result;
        }

        return $response;
    }

    /**
     * @return Service\Manager\ManagerInterface
     */
    private function getServiceManager()
    {
        if (null == $this->serviceManager) {
            $this->serviceManager = new Service\Manager\Manager();
        }

        return $this->serviceManager;
    }
}
