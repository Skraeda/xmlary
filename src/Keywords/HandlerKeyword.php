<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;

/**
 * Handler keyword to create a custom function handler when generating.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class HandlerKeyword implements XmlKeyword
{

    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        if (is_callable($value)) {
            $value($doc, $parent);
        }
    }
}
