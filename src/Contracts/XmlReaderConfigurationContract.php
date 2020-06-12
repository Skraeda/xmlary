<?php

namespace Skraeda\Xmlary\Contracts;

/**
 * XmlReaderConfiguration Contract.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlReaderConfigurationContract
{
    /**
     * Get prefix for keywords.
     *
     * @return string
     */
    public function getKeywordPrefix(): string;

    /**
     * Get Attribute keyword string.
     *
     * @return string
     */
    public function getAttributeKeyword(): string;

    /**
     * Get Value keyword string.
     *
     * @return string
     */
    public function getValueKeyword(): string;

    /**
     * Get Config keyword string.
     *
     * @return string
     */
    public function getConfigKeyword(): string;
}
