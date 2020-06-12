<?php

namespace Skraeda\Xmlary\Contracts;

use Skraeda\Xmlary\XmlReaderNode;

/**
 * XmlReaderNodeConfiguration Contract.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
interface XmlReaderNodeConfigurationContract
{
    /**
     * Execute callback handler after node is created.
     *
     * @param \Skraeda\Xmlary\XmlReaderNode $node
     * @return void
     */
    public function callback(XmlReaderNode $node): void;

    /**
     * Execute value converter and return new value.
     *
     * @param mixed $oldValue
     * @return mixed
     */
    public function convert($oldValue);

    /**
     * Determine if node should always be array.
     *
     * @return boolean
     */
    public function isArray(): bool;

    /**
     * Determine the new name of a node.
     *
     * @param string $oldName
     * @return string
     */
    public function rename(string $oldName): string;
}
