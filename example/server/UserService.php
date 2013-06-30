<?php
namespace Example;

use JsonRpcLib\Server\Service\Wrapper\Annotation\Expose;

class UserService
{
    public function getId()
    {
        return 5;
    }

    /**
     * @Expose
     */
    public function getName()
    {
        return 'Kevin';
    }

    public function setData($name, $address)
    {
        return true;
    }
}
