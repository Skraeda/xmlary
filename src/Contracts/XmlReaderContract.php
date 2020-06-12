<?php

namespace Skraeda\Xmlary\Contracts;

/**
 * XmlReader Contract.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlReaderContract
{
    /**
     * Parse XML string into an array based on a config mapping.
     *
     * @param string $xml
     * @param array $config
     * @return array
     */
    public function parse(string $xml, array $config = []): array;
}
