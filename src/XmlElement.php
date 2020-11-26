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
    public function xmlSerialize(): array
    {
        return reset(parent::xmlSerialize());
    }
}
