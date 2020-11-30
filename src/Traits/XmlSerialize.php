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
            if ($attributes = $this->xmlSerializeAttributes($prop->getName())) {
                $xml[$prop->getName()] = [
                    $this->xmlSerializeAttributeKeyword()   => $attributes,
                    $this->xmlSerializeValueKeyword()       => $this->xmlSerializeValue($value)
                ];
            } else {
                $xml[$prop->getName()] = $this->xmlSerializeValue($value);
            }
        }

        if ($namespaces = $this->xmlSerializeNamespaces()) {
            $xml[$this->xmlSerializeNamespaceKeyword()] = $namespaces;
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
     * @param string $prop
     * @return array
     */
    protected function xmlSerializeAttributes(string $prop): array
    {
        return [];
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
