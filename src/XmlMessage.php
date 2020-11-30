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
    protected function xmlSerializeAttributes(string $prop): array
    {
        $accessor = "${prop}Attributes";

        if (!method_exists($this, $accessor)) {
            return [];
        }
        
        return $this->{$accessor}();
    }

    /**
     * Optional namespace declarations
     *
     * @return array
     */
    protected function xmlSerializeNamespaces(): array
    {
        $ns = $this->namespace();

        if (is_array($ns)) {
            return $ns;
        } elseif (is_string($ns)) {
            return [ $ns ];
        }

        return [];
    }

    /**
     * Optionally define namespace for this Message
     *
     * @return mixed
     */
    protected function namespace()
    {
        return null;
    }
}
