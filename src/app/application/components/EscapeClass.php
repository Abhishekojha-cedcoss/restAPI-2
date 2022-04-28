<?php

declare(strict_types=1);

namespace App\Application\Components;

use Phalcon\Escaper;

/**
 * EscapeClass class
 * Sanitizes the inputs.
 */
final class EscapeClass
{
    /**
     * sanitize function
     * REturn the inputs after escape Html operation
     * @param [string] $val
     * @return string
     */
    public function sanitize(string $val): string
    {
        $escaper = new Escaper();
        return $escaper->escapeHtml($val);
    }
}