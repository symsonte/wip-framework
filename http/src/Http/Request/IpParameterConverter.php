<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\Parameter\ConvertionStorer;
use Symsonte\Call\ParameterConverter;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_converter']
 * })
 */
class IpParameterConverter implements ParameterConverter
{
    /**
     * @var ConvertionStorer
     */
    private $convertionStorer;

    /**
     * @var Http\Server
     */
    private $server;

    /**
     * @param ConvertionStorer $convertionStorer
     * @param Http\Server           $server
     */
    public function __construct(
        ConvertionStorer $convertionStorer,
        Http\Server $server
    ) {
        $this->convertionStorer = $convertionStorer;
        $this->server = $server;
    }

    /**
     * @param string  $class
     * @param string  $method
     * @param mixed[] $parameters
     *
     * @return array
     */
    public function convert(
        string $class,
        string $method,
        array $parameters
    ) {
        $convertions = [];
        
        $key = $this->convertionStorer->find(
            $class,
            $method,
            "http\\request\\ip"
        );

        if (!$key) {
            return $convertions;
        }

        $convertions[$key] = $this->server->resolveIp();

        return $convertions;
    }
}