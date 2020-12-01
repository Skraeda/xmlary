<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMElement;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;

/**
 * Define keyword to create localized namespace.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class DefineKeyword implements XmlKeyword
{
    /**
     * Namespaces map
     *
     * @var array
     */
    protected $namespaces = [
        'http://www.w3.org/2000/xmlns/' => 'xmlns'
    ];

    /**
     * Default fallback namespace
     *
     * @var string
     */
    protected $defaultNamespace = 'http://www.w3.org/2000/xmlns/';

    /**
     * Constructor
     *
     * @param array $namespaces
     * @param string|null $defaultNamespace
     */
    public function __construct(array $namespaces = [], ?string $defaultNamespace = null)
    {
        foreach ($namespaces as $uri => $prefix) {
            $this->namespaces[$uri] = $prefix;
        }

        if ($defaultNamespace) {
            $this->defaultNamespace =$defaultNamespace;
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
                'value' => $url,
                'namespace' => $namespace
            ] = $value;
        } else {
            $url = (string) $value;
        }

        if (!isset($namespace) || !$namespace) {
            $namespace = $this->defaultNamespace;
        }

        $qn = sprintf("%s:%s", $this->namespaces[$namespace], $this->namespaces[$url]);

        $parent->setAttributeNS($namespace, $qn, $url);
    }
}
