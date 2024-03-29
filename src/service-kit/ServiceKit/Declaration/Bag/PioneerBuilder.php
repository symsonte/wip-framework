<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration\ScalarArgument;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\Service\Declaration\TaggedServicesArgument;
use Symsonte\ServiceKit\Declaration;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PioneerBuilder
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param string $cacheDir
     */
    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function build()
    {
        return new Bag(
            array_merge(
                $this->buildBuilderDeclarations(),
                $this->buildCacherDeclarations(),
                $this->buildLoaderDeclarations()
            )
        );
    }

    /**
     * @return Declaration[]
     */
    private function buildBuilderDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.yaml_file_builder',
                    'Symsonte\Resource\YamlFileBuilder',
                    false,
                    []
                ),
                true,
                false,
                false,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.resource.builder']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.annotation_file_builder',
                    'Symsonte\Resource\AnnotationFileBuilder',
                    false,
                    []
                ),
                true,
                false,
                false,
                false,
                [
                    ['key' => 2, 'name' => 'symsonte.resource.builder']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.dir_builder',
                    'Symsonte\Resource\DirBuilder',
                    false,
                    []
                ),
                true,
                false,
                false,
                false,
                [
                    ['key' => 3, 'name' => 'symsonte.resource.builder']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.builder',
                    'Symsonte\Resource\DelegatorBuilder',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.resource.builder'),
                    ]
                ),
                true,
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildCacherDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.cacher.dir_modification_time_approver',
                    'Symsonte\Resource\Cacher\DirModificationTimeApprover',
                    false,
                    [
                        new ServiceArgument('symsonte.resource.dir_slice_reader'),
                        new ServiceArgument('symsonte.resource.filesystem_storer'),
                        new ServiceArgument('symsonte.resource.cacher.file_modification_time_approver'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.resource.cacher.approver']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.filesystem_storer',
                    'Symsonte\Resource\FilesystemStorer',
                    false,
                    [
                        new ScalarArgument($this->cacheDir),
                    ]
                ),
                false,
                true,
                false,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.filesystem_time_storer',
                    'Symsonte\Resource\FilesystemTimeStorer',
                    false,
                    [
                        new ScalarArgument($this->cacheDir),
                    ]
                ),
                false,
                true,
                false,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.cacher.file_modification_time_approver',
                    'Symsonte\Resource\Cacher\FileModificationTimeApprover',
                    false,
                    [
                        new ServiceArgument('symsonte.resource.filesystem_time_storer'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 2, 'name' => 'symsonte.resource.cacher.approver']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.cacher',
                    'Symsonte\ServiceKit\Resource\Cacher',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.resource.cacher.approver'),
                        new ServiceArgument('symsonte.resource.filesystem_storer'),
                    ]
                ),
                false,
                false,
                false,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.perpetual_cacher',
                    'Symsonte\ServiceKit\Resource\PerpetualCacher',
                    false,
                    [
                        new ServiceArgument('symsonte.resource.filesystem_storer')
                    ]
                ),
                false,
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildLoaderDeclarations()
    {
        return array_merge(
            $this->buildReaderDeclarations(),
            $this->buildNormalizerDeclarations(),
            $this->buildCompilerDeclarations(),
            [
                new Declaration(
                    new ConstructorDeclaration(
                        'symsonte.service_kit.resource.loader',
                        'Symsonte\ServiceKit\Resource\Loader',
                        false,
                        [
                            new TaggedServicesArgument('symsonte.resource.builder'),
                            new TaggedServicesArgument('symsonte.resource.slice_reader'),
                            new TaggedServicesArgument('symsonte.service_kit.resource.normalizer'),
                            new TaggedServicesArgument('symsonte.service_kit.resource.compiler'),
                        ]
                    ),
                    false,
                    true,
                    false,
                    false,
                    [],
                    [],
                    []
                ),
            ]
        );
    }

    /**
     * @return Declaration[]
     */
    private function buildReaderDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.data_slice_reader',
                    'Symsonte\Resource\DataSliceReader',
                    false
                ),
                false,
                true,
                false,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.yaml_doc_parser',
                    'Symsonte\Resource\YamlDocParser',
                    false
                ),
                false,
                true,
                false,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.annotation_file_slice_reader',
                    'Symsonte\Resource\AnnotationFileSliceReader',
                    false,
                    [
                        new ServiceArgument('symsonte.resource.data_slice_reader'),
                        new ServiceArgument('symsonte.resource.yaml_doc_parser'),
                    ]
                ),
                false,
                false,
                true,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.resource.file_slice_reader'],
                    ['key' => 1, 'name' => 'symsonte.resource.slice_reader']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.yaml_file_slice_reader',
                    'Symsonte\Resource\YamlFileSliceReader',
                    false,
                    [
                        new ServiceArgument('symsonte.resource.data_slice_reader'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 2, 'name' => 'symsonte.resource.file_slice_reader'],
                    ['key' => 2, 'name' => 'symsonte.resource.slice_reader']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.dir_slice_reader',
                    'Symsonte\Resource\DirSliceReader',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.resource.builder'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.files_slice_reader',
                    'Symsonte\Resource\FilesSliceReader',
                    false,
                    [
                        new ServiceArgument('symsonte.resource.dir_slice_reader'),
                        new TaggedServicesArgument('symsonte.resource.file_slice_reader'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 3, 'name' => 'symsonte.resource.slice_reader']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.resource.slice_reader',
                    'Symsonte\Resource\DelegatorSliceReader',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.resource.slice_reader'),
                    ]
                ),
                false,
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildNormalizerDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.service_annotation_file_normalizer',
                    'Symsonte\ServiceKit\Resource\ServiceAnnotationFileNormalizer',
                    false
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.service_kit.resource.annotation_file_normalizer']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.annotation_file_normalizer',
                    'Symsonte\ServiceKit\Resource\AnnotationFileNormalizer',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.annotation_file_normalizer'),
                    ]
                ),
                false,
                false,
                true,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.service_kit.resource.file_normalizer'],
                    ['key' => 1, 'name' => 'symsonte.service_kit.resource.normalizer']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.files_normalizer',
                    'Symsonte\ServiceKit\Resource\FilesNormalizer',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.file_normalizer'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.service_kit.resource.normalizer']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.normalizer',
                    'Symsonte\Resource\DelegatorNormalizer',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.normalizer'),
                    ]
                ),
                false,
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }

    /**
     * @return Declaration[]
     */
    private function buildCompilerDeclarations()
    {
        return [
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.argument.service_compiler',
                    'Symsonte\ServiceKit\Resource\Argument\ServiceCompiler',
                    false
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.service_kit.resource.argument.compiler']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.argument.tagged_services_compiler',
                    'Symsonte\ServiceKit\Resource\Argument\TaggedServicesCompiler',
                    false
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 2, 'name' => 'symsonte.service_kit.resource.argument.compiler']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.argument.parameter_compiler',
                    'Symsonte\ServiceKit\Resource\Argument\ParameterCompiler',
                    false
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 3, 'name' => 'symsonte.service_kit.resource.argument.compiler']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.service_compiler',
                    'Symsonte\ServiceKit\Resource\ServiceCompiler',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.argument.compiler'),
                    ]
                ),
                false,
                true,
                false,
                false,
                [
                    ['key' => 1, 'name' => 'symsonte.service_kit.resource.compiler']
                ],
                [],
                []
            ),
            new Declaration(
                new ConstructorDeclaration(
                    'symsonte.service_kit.resource.compiler',
                    'Symsonte\Resource\DelegatorCompiler',
                    false,
                    [
                        new TaggedServicesArgument('symsonte.service_kit.resource.compiler'),
                    ]
                ),
                false,
                false,
                false,
                false,
                [],
                [],
                []
            ),
        ];
    }
}
