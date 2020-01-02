<?php
declare(strict_types=1);

namespace RZ\Roadiz\GraphQL;

use GraphQL\Doctrine\DefaultFieldResolver;
use GraphQL\Doctrine\Definition\EntityID;
use GraphQL\Doctrine\Types;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RZ\Roadiz\Core\Entities\Font;
use RZ\Roadiz\GraphQL\Type\DateTimeType;
use RZ\Roadiz\GraphQL\Type\MixedType;
use RZ\Roadiz\GraphQL\Type\SimpleArrayType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

final class GraphQLServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->extend('routeCollection', function (RouteCollection $routeCollection, Container $c) {
            $resourcesFolder = dirname(__FILE__) . '/Resources';
            $locator = new FileLocator([
                $resourcesFolder,
                $resourcesFolder . '/routing',
                $resourcesFolder . '/config',
            ]);
            $loader = new YamlFileLoader($locator);
            $routeCollection->addCollection($loader->load('routes.yml'));
            return $routeCollection;
        });

        $container['datetime'] = function (Container $c) {
            return new DateTimeType();
        };

        $container['\DateTime'] = function (Container $c) {
            return $c['datetime'];
        };

        $container['simple_array'] = function (Container $c) {
            return new SimpleArrayType();
        };

        $container['mixed'] = function (Container $c) {
            return new MixedType();
        };

        /**
         * @param Container $c
         *
         * @return Types
         */
        $container[Types::class] = function (Container $c) {
            // Configure default field resolver to be able to use getters
            GraphQL::setDefaultFieldResolver(new DefaultFieldResolver());
            return new Types($c['em'], new \Pimple\Psr11\Container($c));
        };

        /**
         * @param Container $c
         *
         * @return Schema
         */
        $container[Schema::class] = function (Container $c) {
            // Build your Schema
            /** @var Types $types */
            $types = $c[Types::class];
            $schema = new Schema([
                'query' => new ObjectType([
                    'name' => 'query',
                    'fields' => [
                        'fonts' => [
                            'type' => Type::listOf($types->getOutput(Font::class)), // Use automated ObjectType for output
                            'args' => [
                                [
                                    'name' => 'filter',
                                    'type' => $types->getFilter(Font::class), // Use automated filtering options
                                ],
                                [
                                    'name' => 'sorting',
                                    'type' => $types->getSorting(Font::class), // Use automated sorting options
                                ],
                            ],
                            'resolve' => function ($root, $args) use ($types) {
                                $queryBuilder = $types->createFilteredQueryBuilder(Font::class, $args['filter'] ?? [], $args['sorting'] ?? []);
                                dump($queryBuilder->getQuery()->getDQL());
                                return $queryBuilder->getQuery()->getResult();
                            },
                        ],
//                        'nodes-sources' => [
//                            'type' => Type::listOf($types->getOutput(NodesSources::class)), // Use automated ObjectType for output
//                            'args' => [
//                                [
//                                    'name' => 'filter',
//                                    'type' => $types->getFilter(NodesSources::class), // Use automated filtering options
//                                ],
//                                [
//                                    'name' => 'sorting',
//                                    'type' => $types->getSorting(NodesSources::class), // Use automated sorting options
//                                ],
//                            ],
//                            'resolve' => function ($root, $args) use ($types) {
//                                $queryBuilder = $types->createFilteredQueryBuilder(NodesSources::class, $args['filter'] ?? [], $args['sorting'] ?? []);
//                                return $queryBuilder->getQuery()->getResult();
//                            },
//                        ],
//                        'translations' => [
//                            'type' => Type::listOf($types->getOutput(Translation::class)), // Use automated ObjectType for output
//                            'args' => [
//                                [
//                                    'name' => 'filter',
//                                    'type' => $types->getFilter(Translation::class), // Use automated filtering options
//                                ],
//                                [
//                                    'name' => 'sorting',
//                                    'type' => $types->getSorting(Translation::class), // Use automated sorting options
//                                ],
//                            ],
//                            'resolve' => function ($root, $args) use ($types) {
//                                $queryBuilder = $types->createFilteredQueryBuilder(Translation::class, $args['filter'] ?? [], $args['sorting'] ?? []);
//                                return $queryBuilder->getQuery()->getResult();
//                            },
//                        ],
//                        'node' => [
//                            'type' => Type::listOf($types->getOutput(Node::class)), // Use automated ObjectType for output
//                            'args' => [
//                                [
//                                    'name' => 'filter',
//                                    'type' => $types->getFilter(Node::class), // Use automated filtering options
//                                ],
//                                [
//                                    'name' => 'sorting',
//                                    'type' => $types->getSorting(Node::class), // Use automated sorting options
//                                ],
//                            ],
//                            'resolve' => function ($root, $args) use ($types) {
//                                $queryBuilder = $types->createFilteredQueryBuilder(Node::class, $args['filter'] ?? [], $args['sorting'] ?? []);
//                                return $queryBuilder->getQuery()->getResult();
//                            },
//                        ],
                    ],
                ]),
//                'mutation' => new ObjectType([
//                    'name' => 'mutation',
//                    'fields' => [
//                        'createPost' => [
//                            'type' => Type::nonNull($types->getOutput(Post::class)),
//                            'args' => [
//                                'input' => Type::nonNull($types->getInput(Post::class)), // Use automated InputObjectType for input
//                            ],
//                            'resolve' => function ($root, $args): void {
//                                // create new post and flush...
//                            },
//                        ],
//                        'updatePost' => [
//                            'type' => Type::nonNull($types->getOutput(Post::class)),
//                            'args' => [
//                                'id' => Type::nonNull(Type::id()), // Use standard API when needed
//                                'input' => $types->getPartialInput(Post::class),  // Use automated InputObjectType for partial input for updates
//                            ],
//                            'resolve' => function ($root, $args): void {
//                                // update existing post and flush...
//                            },
//                        ],
//                    ],
//                ]),
            ]);
            return $schema;
        };
    }
}
