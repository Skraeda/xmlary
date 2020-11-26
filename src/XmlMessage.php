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
    protected function xmlSerializeMutateValue($prop, $val)
    {
        $mutator = "${prop}Mutator";

        if (method_exists($this, $mutator)) {
            return $this->{$mutator}($val);
        }

        return $val;
    }
}
