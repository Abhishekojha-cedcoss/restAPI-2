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
     * @param [type] $val
     * @return void
     */
    public function sanitize($val)
    {
        $escaper = new Escaper();
        return $escaper->escapeHtml($val);
    }
}