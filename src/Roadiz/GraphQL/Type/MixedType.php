<?php
declare(strict_types=1);

namespace RZ\Roadiz\GraphQL\Type;

use GraphQL\Type\Definition\ScalarType;

final class MixedType extends ScalarType
{
    public function serialize($value)
    {
        return $value;
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        return $valueNode->value;
    }
}
