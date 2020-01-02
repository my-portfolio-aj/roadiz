<?php
declare(strict_types=1);

namespace RZ\Roadiz\GraphQL\Type;

use GraphQL\Type\Definition\ScalarType;

final class SimpleArrayType extends ScalarType
{
    public function serialize($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }
        return $value;
    }

    public function parseValue($value)
    {
        return json_decode($value, true);
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        return $valueNode->value;
    }
}
