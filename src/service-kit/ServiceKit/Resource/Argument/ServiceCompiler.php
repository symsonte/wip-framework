<?php

namespace Symsonte\ServiceKit\Resource\Argument;

use Symsonte\Service\Declaration\ServiceArgument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.argument.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.argument.compiler']
 * })
 */
class ServiceCompiler implements Compiler
{
    /**
     * {@inheritdoc}
     */
    public function compile($argument)
    {
        if ($argument[0] != '@') {
            throw new UnsupportedArgumentException($argument);
        }

        $optional = false;
        if ($argument[1] == '?') {
            $argument = str_replace('?', '', $argument);
            
            $optional = true;
        }

        return new ServiceArgument(substr($argument, 1), $optional);
    }
}
