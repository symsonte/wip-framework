<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.builder']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.builder']
 * })
 */
class YamlFileBuilder implements Builder
{
    /**
     * {@inheritdoc}
     *
     * @return YamlFileResource
     */
    public function build($metadata)
    {
        $metadata = $this->fixMetadata($metadata);

        if (!$this->support($metadata)) {
            throw new UnsupportedMetadataException($metadata);
        }

        return new YamlFileResource($metadata['file']);
    }

    /**
     * @param mixed $metadata
     *
     * @return bool
     */
    private function support($metadata)
    {
        if (!isset($metadata['file'])) {
            return false;
        }

        if (isset($metadata['type'])) {
            if ('yaml' != $metadata['type']) {
                return false;
            }

            return true;
        }

        if (!in_array(pathinfo($metadata['file'], PATHINFO_EXTENSION), ['yaml', 'yml'])) {
            return false;
        }

        return true;
    }

    /**
     * Fixes metadata if it comes as short version.
     *
     * @param array $metadata
     *
     * @return array
     */
    private function fixMetadata($metadata)
    {
        if (is_string($metadata)) {
            $metadata = ['file' => $metadata];
        }

        return $metadata;
    }
}
