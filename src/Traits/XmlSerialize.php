<?php

namespace Skraeda\Xmlary\Traits;

use ReflectionClass;
use Skraeda\Xmlary\Contracts\XmlSerializable;

/**
 * Trait to XmlSerialize a simple model.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
trait XmlSerialize
{
    /**
     * Return XML array.
     *
     * @return array
     */
    public function xmlSerialize(): array
    {
        $c = new ReflectionClass($this);

        $xml = [];

        foreach ($c->getProperties() as $prop) {
            $prop->setAccessible(true);
            $value = $this->xmlSerializeMutateValue($prop->getName(), $prop->getValue($this));
            $tag = implode(':', array_filter([
                $this->xmlSerializeNamespacePrefix($prop->getName()),
                $this->xmlSerializePropToTag($prop->getName())
            ]));
            if ($attributes = $this->xmlSerializeAttributes($prop->getName())) {
                $xml[$tag] = [
                    $this->xmlSerializeAttributeKeyword()   => $attributes,
                    $this->xmlSerializeValueKeyword()       => $this->xmlSerializeValue($value)
                ];
            } else {
                $xml[$tag] = $this->xmlSerializeValue($value);
            }
        }

        if ($namespaces = $this->xmlSerializeNamespaces()) {
            $xml[$this->xmlSerializeNamespaceKeyword()] = $namespaces;
        }

        if ($attributes = $this->xmlSerializeAttributes()) {
            $xml['@attributes'] = $attributes;
        }

        return [ $c->getShortName() => $xml ];
    }

    /**
     * XML Serialize a single value.
     *
     * @param mixed $val
     * @return mixed
     */
    protected function xmlSerializeValue($val)
    {
        if (is_array($val)) {
            return array_map([$this, 'xmlSerializeValue'], $val);
        }

        return $val instanceof XmlSerializable ? $val->xmlSerialize() : $val;
    }

    /**
     * Optional value mutator before a property is converted to XML element
     *
     * @param string $prop
     * @param mixed $val
     * @return mixed
     */
    protected function xmlSerializeMutateValue(string $prop, $val)
    {
        return $val;
    }

    /**
     * Optional namespace declarations
     *
     * @return array
     */
    protected function xmlSerializeNamespaces(): array
    {
        return [];
    }

    /**
     * Optional attribute declarations
     *
     * @param string|null $prop
     * @return array
     */
    protected function xmlSerializeAttributes(?string $prop = null): array
    {
        return [];
    }

    /**
     * Optionally set a namespace prefix to tags generated
     *
     * @param string $prop
     * @return string
     */
    protected function xmlSerializeNamespacePrefix(string $prop): string
    {
        return '';
    }

    /**
     * Optionally define rules to convert a prop name to a tag
     *
     * @param string $prop
     * @return string
     */
    protected function xmlSerializePropToTag(string $prop): string
    {
        return $prop;
    }

    /**
     * Optional override for Attributes keyword
     *
     * @return string
     */
    protected function xmlSerializeAttributeKeyword(): string
    {
        return '@attributes';
    }

    /**
     * Optional override for Value keyword
     *
     * @return string
     */
    protected function xmlSerializeValueKeyword(): string
    {
        return '@value';
    }

    /**
     * Optional override for Namespace keyword
     *
     * @return string
     */
    protected function xmlSerializeNamespaceKeyword(): string
    {
        return '@namespace';
    }
}
