<?php

namespace Skraeda\Xmlary\Keywords;

use DOMDocument;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlKeyword;
use Skraeda\Xmlary\Contracts\XmlValidatorContract;

/**
 * Attribute keyword.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class AttributeKeyword implements XmlKeyword
{
    /**
     * XML Validator.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValidatorContract
     */
    protected $validator;

    /**
     * Namespaces lookup map
     *
     * @var array
     */
    protected $namespaces;

    /**
     * Constructor.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlValidatorContract $validator
     */
    public function __construct(XmlValidatorContract $validator, array $namespaces = [])
    {
        $this->validator = $validator;
        $this->namespaces = $namespaces;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        foreach ($value as $name => $v) {
            [ 'ns' => $ns, 'tag' => $tag ] = $this->parseAttribute($name);
            $this->validator->validateAttribute($tag, $v);
            if ($ns !== null && array_key_exists($ns, $this->namespaces)) {
                $attribute = $doc->createAttributeNS($this->namespaces[$ns], $name);
            } else {
                $attribute = $doc->createAttribute($name);
            }
            $attribute->value = $v;
            $parent->appendChild($attribute);
        }
    }

    /**
     * Parse attribute qualified name to namespace and tag
     *
     * @param string $name
     * @return array
     */
    protected function parseAttribute(string $name): array
    {
        $parts = explode(':', $name);

        if (count($parts) === 2) {
            return [
                'ns' => $parts[0],
                'tag' => $parts[1]
            ];
        } else {
            return [
                'ns' => null,
                'tag' => $name
            ];
        }
    }
}
