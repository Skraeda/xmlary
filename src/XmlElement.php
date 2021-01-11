<?php

namespace Skraeda\Xmlary;

/**
 * Variation of XmlMessage to exclude XML root element.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
abstract class XmlElement extends XmlMessage
{
    /**
     * {@inheritDoc}
     */
    protected function xmlSerializeRootName(): ?string
    {
        return null;
    }
}
