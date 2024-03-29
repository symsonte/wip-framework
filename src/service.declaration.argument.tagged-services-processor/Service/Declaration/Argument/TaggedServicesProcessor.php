<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Container;
use Symsonte\Service\Declaration\TaggedServicesArgument;
use Symsonte\Service\Declaration\TagStorer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class TaggedServicesProcessor implements Processor
{
    /**
     * @var TagStorer
     */
    private $storer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param TagStorer $storer
     */
    public function __construct(TagStorer $storer)
    {
        $this->storer = $storer;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param TaggedServicesArgument $argument
     *
     * @throws UnsupportedArgumentException if the argument is not supported
     *
     * @return object[]
     */
    public function process($argument)
    {
        if ($this->support($argument) === false) {
            throw new UnsupportedArgumentException($argument);
        }

        $instances = [];
        $ids = $this->storer->get($argument->getTag());
        foreach ($ids as $key => $id) {
            $instances[$key] = $this->container->get($id);
        }

        return $instances;
    }

    /**
     * @param $argument
     *
     * @return bool
     */
    private function support($argument)
    {
        return $argument instanceof TaggedServicesArgument;
    }
}
