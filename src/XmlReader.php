<?php

namespace Skraeda\Xmlary;

use DOMCharacterData;
use DOMDocument;
use DOMElement;
use DOMNode;
use Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract;
use Skraeda\Xmlary\Contracts\XmlReaderContract;
use Skraeda\Xmlary\Exceptions\XmlReaderException;
use Throwable;

/**
 * XmlReader implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReader implements XmlReaderContract
{
    /**
     * XmlReader configuration
     *
     * @var \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract
     */
    protected $config;
    
    /**
     * XmlReader constructor.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract|null $config
     */
    public function __construct(?XmlReaderConfigurationContract $config = null)
    {
        $this->config = $config ?? new XmlReaderConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(string $xml, array $config = []): array
    {
        try {
            $doc = new DOMDocument;
            $doc->loadXML($xml);
            $root = $this->buildReaderNodeTree($doc->documentElement, $config);
            return [ $root->getName() => $this->treeToArray($root) ];
        } catch (Throwable $e) {
            throw XmlReaderException::wrap($e);
        }
    }

    /**
     * Get Config
     *
     * @return \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract
     */
    public function getConfiguration(): XmlReaderConfigurationContract
    {
        return $this->config;
    }
    
    /**
     * Set Config
     *
     * @param \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract $config
     * @return self
     */
    public function setConfiguration(XmlReaderConfigurationContract $config): self
    {
        $this->config = $config;
        
        return $this;
    }

    /**
     * Build XmlReaderNode tree
     *
     * @param \DOMNode $node
     * @param array $config
     * @return XmlReaderNode
     */
    protected function buildReaderNodeTree(DOMNode $node, array $config = []): XmlReaderNode
    {
        $readerNodeConfig = $config[$node->nodeName] ?? [];
        $readerNode = new XmlReaderNode($node, $readerNodeConfig[$this->configKeyword()] ?? null);

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $readerNode->addChild($this->buildReaderNodeTree($child, $readerNodeConfig));
            } elseif ($child instanceof DOMCharacterData) {
                $readerNode->setValue($child->data);
            }
        }

        return $readerNode;
    }

    /**
     * Convert XmlReaderNode tree to array.
     *
     * @param XmlReaderNode $node
     * @return array
     */
    protected function treeToArray(XmlReaderNode $node): array
    {
        $tree = [];

        if (!empty($attributes = $node->getAttributes())) {
            $tree[$this->attributeKeyword()] = $attributes;
        }

        if ($node->isLeaf()) {
            $tree[$this->valueKeyword()] = $node->getValue();
        }

        foreach ($node->getChildren() as $name => $childGroup) {
            $tree[$name] = [];
            $forceArray = false;
            foreach ($childGroup as $child) {
                $tree[$name][] = $this->treeToArray($child);
                $forceArray = $child->getConfiguration()->isArray();
            }
            if (count($tree[$name]) === 1 && !$forceArray) {
                $tree[$name] = reset($tree[$name]);
            }
        }

        return $tree;
    }

    /**
     * Create keyword with prefix.
     *
     * @param string $word
     * @return string
     */
    protected function keyword(string $word): string
    {
        return $this->config->getKeywordPrefix().$word;
    }

    /**
     * Get prefixed config keyword.
     *
     * @return string
     */
    protected function configKeyword(): string
    {
        return $this->keyword($this->config->getConfigKeyword());
    }

    /**
     * Get prefixed attribute keyword.
     *
     * @return string
     */
    protected function attributeKeyword(): string
    {
        return $this->keyword($this->config->getAttributeKeyword());
    }

    /**
     * Get prefixed value keyword.
     *
     * @return string
     */
    protected function valueKeyword(): string
    {
        return $this->keyword($this->config->getValueKeyword());
    }
}
