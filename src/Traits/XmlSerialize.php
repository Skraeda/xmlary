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
            $xml[$prop->getName()] = $this->xmlSerializeValue($value);
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
    protected function xmlSerializeMutateValue($prop, $val)
    {
        return $val;
    }
}
