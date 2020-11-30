<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMElement;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;

/**
 * Namespace keyword to create localized namespace.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class NamespaceKeyword implements XmlKeyword
{
    /**
     * Namespaces map
     *
     * @var array
     */
    protected $namespaces = [
        'xmlns' => 'http://www.w3.org/2000/xmlns/'
    ];

    /**
     * Constructor
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces = [])
    {
        foreach ($namespaces as $namespace => $uri) {
            $this->namespaces[$namespace] = $uri;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        if ($parent instanceof DOMElement) {
            if (is_array($value)) {
                foreach ($value as $ns) {
                    $parent->setAttributeNS($this->namespaces['xmlns'], 'xmlns:'.$ns, $this->namespaces[$ns]);
                }
            } else {
                $parent->setAttributeNS($this->namespaces['xmlns'], 'xmlns:'.$value, $this->namespaces[$value]);
            }
        }
    }
}
