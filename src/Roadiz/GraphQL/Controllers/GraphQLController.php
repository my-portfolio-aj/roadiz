<?php
declare(strict_types=1);

namespace RZ\Roadiz\GraphQL\Controllers;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use RZ\Roadiz\CMS\Controllers\AppController;
use RZ\Roadiz\Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GraphQLController extends AppController
{
    public function defaultAction(Request $request)
    {
        $rawInput = $request->getContent();
        $input = json_decode($rawInput, true);
        $query = $input['query'];

        $variableValues = isset($input['variables']) ? $input['variables'] : null;
        $result = GraphQL::executeQuery($this->get(Schema::class), $query, null, null, $variableValues);

        return new JsonResponse($result->toArray());
    }

    public function schemaAction(Request $request)
    {
        /** @var Schema $schema */
        $schema = $this->get(Schema::class);
        return new JsonResponse($schema->getConfig()->getQuery()->config);
    }
}
