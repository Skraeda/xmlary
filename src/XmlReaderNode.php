<?php

namespace Skraeda\Xmlary;

use DOMNode;
use Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract;

/**
 * Single node for XmlReader.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderNode
{
    /**
     * DOMNode.
     *
     * @var \DOMNode
     */
    protected $node;

    /**
     * Child nodes.
     *
     * @var array
     */
    protected $children = [];

    /**
     * Configuration contract.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract
     */
    protected $config;

    /**
     * Value of the node
     *
     * @var mixed
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param \DOMNode $node
     * @param \Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract|null $config
     */
    public function __construct(DOMNode $node, ?XmlReaderNodeConfigurationContract $config = null)
    {
        $this->node = $node;
        $this->config = $config ?? new XmlReaderNodeConfiguration;
        $this->config->callback($this);
    }

    /**
     * Get underlying DOMNode.
     *
     * @return \DOMNode
     */
    public function getNode(): DOMNode
    {
        return $this->node;
    }

    /**
     * Get configuration instance.
     *
     * @return \Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract
     */
    public function getConfiguration(): XmlReaderNodeConfigurationContract
    {
        return $this->config;
    }

    /**
     * Get the data value of this node.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->config->convert($this->value);
    }

    /**
     * Set the node value.
     *
     * @param mixed $value
     * @return self
     */
    public function setValue($value): self
    {
        $this->value = $value;
        
        return $this;
    }

    /**
     * Get DOMNode attributes as key => value array.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [];
        foreach ($this->node->attributes as $attribute) {
            $attributes[$attribute->nodeName] = $attribute->value;
        }
        return $attributes;
    }

    /**
     * Get the name of this node.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->config->rename($this->node->nodeName);
    }

    /**
     * Add a reader node as child to this one.
     *
     * @param XmlReaderNode $node
     * @return self
     */
    public function addChild(XmlReaderNode $node): self
    {
        if (!array_key_exists($node->getName(), $this->children)) {
            $this->children[$node->getName()] = [];
        }

        $this->children[$node->getName()][] = $node;
        
        return $this;
    }

    /**
     * Get children as grouped by name values.
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Determine if this is a leaf node.
     *
     * @return boolean
     */
    public function isLeaf(): bool
    {
        return count($this->children) === 0;
    }
}
