<?php

namespace JsonRpcLib\Server\Service\Wrapper;

class Helper
{
    /**
     * @param  array   $parameters
     * @param  array   $parametersNames
     * @param  int     $numberOfRequiredParameters
     * @return boolean
     */
    public function isValidParameters(
        array $parameters,
        array $parametersNames,
        $numberOfRequiredParameters
    )
    {
        foreach ($parametersNames as $name) {
            if ($numberOfRequiredParameters-- <= 0 || array_key_exists($name, $parameters)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param  array $parameters
     * @param  int   $numberOfRequiredParameters
     * @param  int   $numberOfParameters
     * @return type
     */
    public function isValidNumberOfParameters(
        array $parameters,
        $numberOfRequiredParameters,
        $numberOfParameters
    )
    {
        $parametersCount = count($parameters);

        $hasRequiredParameters      = $parametersCount >= $numberOfRequiredParameters;
        $hasValidNumberParameters   = $parametersCount <= $numberOfParameters;

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
