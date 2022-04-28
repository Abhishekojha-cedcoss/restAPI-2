<?php

namespace App\Application\Components;

use Phalcon\Escaper;

/**
 * EscapeClass class
 * Sanitizes the inputs.
 */
class EscapeClass
{
    /**
     * sanitize function
     * REturn the inputs after escape Html operation
     * @param [string] $val
     * @return string
     */
    public function sanitize(string $val)
    {
        $escaper = new Escaper();
        return $escaper->escapeHtml($val);
    }
}