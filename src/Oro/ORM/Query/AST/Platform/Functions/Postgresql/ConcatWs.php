<?php
declare(strict_types=1);

namespace Oro\ORM\Query\AST\Platform\Functions\Postgresql;

use Doctrine\ORM\Query\SqlWalker;

use Oro\ORM\Query\AST\Functions\String\ConcatWs as Base;
use Oro\ORM\Query\AST\Platform\Functions\PlatformFunctionNode;

class ConcatWs extends PlatformFunctionNode
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        $strings = [];
        $stringExpressions = $this->parameters[Base::STRINGS_KEY];
        foreach ($stringExpressions as $stringExp) {
            $strings[] = $sqlWalker->walkStringPrimary($stringExp);
        }

        return sprintf(
            'CONCAT_WS(%s, %s)',
            $sqlWalker->walkStringPrimary($this->parameters[Base::SEPARATOR_KEY]),
            \implode(', ', $strings)
        );
    }
}
