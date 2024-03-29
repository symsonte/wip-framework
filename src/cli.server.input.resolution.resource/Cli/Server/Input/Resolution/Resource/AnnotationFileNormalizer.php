<?php

namespace Symsonte\Cli\Server\Input\Resolution\Resource;

use Symsonte\Resource\AnnotationFileResource;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.resolution.resource.file_normalizer', 'symsonte.cli.server.input.resolution.resource.normalizer']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.resolution.resource.file_normalizer', 'symsonte.cli.server.input.resolution.resource.normalizer']
 * })
 */
class AnnotationFileNormalizer implements Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($data, $resource)
    {
        if (!$resource instanceof AnnotationFileResource) {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $data = $data['value'];

        // is the command the default value?
        if (count($data['value']) == 1
            && key($data['value']) === 0
        ) {
            $data['value']['command'] = $data['value'][0];
            unset($data['value'][0]);
        }

        $normalization = new Normalization();

        $normalization->key = $this->generateKey($data['metadata']['class'], $data['method']);

        if (isset($data['value']['command'])) {
            $normalization->matches['command'] = $data['value']['command'];
        }

        return $normalization;
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return string
     */
    private function generateKey($class, $method)
    {
        return sprintf(
            '%s:%s',
            strtolower(
                strtr(
                    preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class),
                    '\\',
                    '.'
                )
            ),
            $method
        );
    }
}
