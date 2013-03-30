<?php

namespace JsonRpcLib\Server\Service\Wrapper;

class Helper
{
    /**
     * @param  \ReflectionFunctionAbstract $reflectionFunction
     * @param  array                       $parameters
     * @return boolean
     */
    public function isValidParameters(\ReflectionFunctionAbstract $reflectionFunction, array $parameters)
    {
        $hasRequiredParameters =
            count($parameters) >= $reflectionFunction->getNumberOfRequiredParameters();

        $hasValidNumberParameters =
            count($parameters) <= $reflectionFunction->getNumberOfParameters();

        return $hasRequiredParameters && $hasValidNumberParameters;
    }

    /**
     * @param  array $parametersNames
     * @param  array $params
     * @return array
     */
    public function normalizeParameters(array $parametersNames, array $params)
    {
        $isAssoc =  array_keys($params) !== range(0, count($params) - 1);

        $parameters = array();

        foreach ($parametersNames as $idx=>$name) {

            $parameterKey = $idx;

            if ($isAssoc) {
                $parameterKey = $name;
            }

            if (array_key_exists($parameterKey, $params)) {
                $parameters[$name] = $params[$parameterKey];
            }
        }

        return $parameters;
    }

    /**
     * @param  \ReflectionFunctionAbstract $reflectionFunction
     * @return array
     */
    public function getParametersNames(\ReflectionFunctionAbstract $reflectionFunction)
    {
        $names = array();
        foreach ($reflectionFunction->getParameters() as $param) {
            $names[] = $param->getName();
        }

        return $names;
    }
}
