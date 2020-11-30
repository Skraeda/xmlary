<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\Traits\XmlSerialize;

/**
 * XmlSerializable message model base.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
abstract class XmlMessage implements XmlSerializable
{
    use XmlSerialize;

    /**
     * Optional value mutator before a property is converted to XML element
     *
     * @param string $prop
     * @param mixed $val
     * @return mixed
     */
    protected function xmlSerializeMutateValue(string $prop, $val)
    {
        $mutator = "${prop}Mutator";

        if (!method_exists($this, $mutator)) {
            return $val;
        }
        
        return $this->{$mutator}($val);
    }

    /**
     * Optionally add attributes to a property before it is converted to XML element
     *
     * @param string $prop
     * @return array
     */
    protected function xmlSerializeAttributes(?string $prop = null): array
    {
        if (!$prop) {
            return $this->attributes();
        }

        $accessor = "${prop}Attributes";

        if (!method_exists($this, $accessor)) {
            return [];
        }
        
        return $this->{$accessor}();
    }

    /**
     * Optionally define rules to convert a prop name to a tag
     *
     * @param string $prop
     * @return string
     */
    protected function xmlSerializePropToTag(string $prop): string
    {
        $converter = "${prop}Tag";

        if (!method_exists($this, $converter)) {
            return $prop;
        }
        
        return $this->{$converter}();
    }

    /**
     * Optional namespace declarations
     *
     * @param string $prop
     * @return string
     */
    protected function xmlSerializeNamespacePrefix(string $prop): string
    {
        $ns = "${prop}Namespace";

        if (method_exists($this, $ns)) {
            return $this->{$ns}();
        }
        
        return $this->namespace();
    }

    /**
     * Optionally define namespace for this Message's elements
     *
     * @return string
     */
    protected function namespace(): string
    {
        return '';
    }

    /**
     * Optionally define attributes for this Message
     *
     * @return array
     */
    protected function attributes(): array
    {
        return [];
    }
}
