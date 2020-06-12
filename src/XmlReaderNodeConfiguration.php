<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\XmlReaderNode;
use Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract;
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;

/**
 * XmlReaderNodeConfigurationContract implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderNodeConfiguration implements XmlReaderNodeConfigurationContract
{
    /**
     * Callback to be executed when XmlReaderNode is constructed.
     *
     * @var \callable|null
     */
    protected $callbackHandler;

    /**
     * Value converter.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlValueConverterContract|\callable|null $converter
     */
    protected $converter;

    /**
     * Boolean flag to determine if node should always be an array.
     *
     * @var boolean
     */
    protected $arrayFlagged;

    /**
     * New name if node should be renamed.
     *
     * @var string|null
     */
    protected $newName;

    /**
     * Constructor.
     *
     * @param string|null $newName
     * @param boolean $array
     * @param \Skraeda\Xmlary\Contracts\XmlValueConverterContract|\callable|null $converter
     * @param \callable $callbackHandler
     */
    public function __construct(?string $newName = null, bool $array = false, $converter = null, $callbackHandler = null)
    {
        $this->newName = $newName;
        $this->arrayFlagged = $array;
        $this->converter = $converter;
        $this->callbackHandler = $callbackHandler;
    }

    /**
     * {@inheritDoc}
     */
    public function callback(XmlReaderNode $node): void
    {
        if ($this->callbackHandler !== null && is_callable($this->callbackHandler)) {
            call_user_func($this->callbackHandler, $node);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function convert($oldValue)
    {
        if ($this->converter !== null) {
            if (is_callable($this->converter)) {
                return call_user_func($this->converter, $oldValue);
            } elseif ($this->converter instanceof XmlValueConverterContract) {
                return $this->converter->convert($oldValue);
            }
        }

        return $oldValue;
    }

    /**
     * {@inheritDoc}
     */
    public function isArray(): bool
    {
        return $this->arrayFlagged;
    }

    /**
     * {@inheritDoc}
     */
    public function rename(string $oldName): string
    {
        return $this->newName ?? $oldName;
    }
}
