<?php

namespace Symsonte\Resource\Cacher;

use Symsonte\Resource\FileResource;
use Symsonte\Resource\Storer;
use Symsonte\Resource\UnsupportedResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.cacher.approver']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.cacher.approver']
 * })
 */
class FileModificationTimeApprover implements Approver
{
    /**
     * @var Storer
     */
    private $storer;

    /**
     * @param Storer $storer
     *
     * @ds\arguments({
     *     storer: '@symsonte.resource.filesystem_time_storer'
     * })
     *
     * @di\arguments({
     *     storer: '@symsonte.resource.filesystem_time_storer'
     * })
     */
    public function __construct(
        Storer $storer
    ) {
        $this->storer = $storer;
    }

    /**
     * {@inheritdoc}
     */
    public function add($resource)
    {
        if (!$resource instanceof FileResource) {
            throw new UnsupportedResourceException($resource);
        }

        $this->storer->add(
            $this->calculateVersion($resource),
            $resource
        );
    }

    /**
     * {@inheritdoc}
     */
    public function approve($resource)
    {
        if (!$resource instanceof FileResource) {
            throw new UnsupportedResourceException($resource);
        }

        return $this->storer->has($resource)
               && $this->storer->get($resource) == $this->calculateVersion($resource);
    }

    /**
     * @param FileResource $resource
     *
     * @return string
     */
    private function calculateVersion(FileResource $resource)
    {
        return filemtime($resource->getFile());
    }
}
