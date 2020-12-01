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
    protected $namespaces = [];

    /**
     * Constructor
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces = [])
    {
        $this->namespaces = $namespaces;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        if ($parent instanceof DOMElement) {
            if (is_array($value)) {
                foreach ($value as $ns) {
                    $this->setAttributeNS($parent, $ns);
                }
            } else {
                $this->setAttributeNS($parent, $value);
            }
        }
    }

    /**
     * Set attribute NS to parent
     *
     * @param \DOMElement $parent
     * @param mixed $value
     * @return void
     */
    protected function setAttributeNS(DOMElement $parent, $value): void
    {
        if (is_array($value)) {
            [
                'value' => $prefix,
                'namespace' => $namespace
            ] = $value;
        } else {
            $prefix = (string) $value;
        }

        if (!isset($namespace) || !$namespace) {
            $keys = array_keys($this->namespaces);
            $namespace = reset($keys);
        }

        $qn = sprintf("%s:%s", $namespace, $prefix);

        $parent->setAttributeNS($this->namespaces[$namespace], $qn, $this->namespaces[$prefix]);
    }
}
