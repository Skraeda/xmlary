<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract;

/**
 * Default config for XmlReader.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderConfiguration implements XmlReaderConfigurationContract
{
    /**
     * {@inheritDoc}
     */
    public function getKeywordPrefix(): string
    {
        return '@';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAttributeKeyword(): string
    {
        return 'attributes';
    }

    /**
     * {@inheritDoc}
     */
    public function getValueKeyword(): string
    {
        return 'value';
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigKeyword(): string
    {
        return 'config';
    }
}
