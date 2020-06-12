<?php

namespace Skraeda\Xmlary\Tests;

use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract;
use Skraeda\Xmlary\XmlReaderNode;
use Skraeda\Xmlary\XmlReaderNodeConfiguration;

/**
 * Unit tests for \Skraeda\Xmlary\XmlReaderNode
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderNodeTest extends TestCase
{
    /**
     * XML writer config mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlReaderNodeConfigurationContract
     */
    protected $config;

    /**
     * Setup the test cases.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->config = Mockery::mock(XmlReaderNodeConfigurationContract::class);
    }

    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlReaderNode(Mockery::mock(DOMNode::class))));
    }

    /** @test */
    public function itHasConfiguration()
    {
        $this->config->shouldReceive('callback');
        $node = new XmlReaderNode(Mockery::mock(DOMNode::class), $this->config);
        $this->assertEquals($this->config, $node->getConfiguration());
    }

    /** @test */
    public function itDefaultsToTheNameOfTheNode()
    {
        $node = new XmlReaderNode(new DOMElement('abc'));
        $this->assertEquals('abc', $node->getName());
        $this->assertEquals('abc', $node->getNode()->nodeName);
    }

    /** @test */
    public function itUsesConfigForRenameIfPresent()
    {
        $node = new XmlReaderNode(new DOMElement('abc'), new XmlReaderNodeConfiguration('newname'));
        $this->assertEquals('newname', $node->getName());
        $this->assertEquals('abc', $node->getNode()->nodeName);
    }

    /** @test */
    public function itReturnsSetValueByDefault()
    {
        $node = new XmlReaderNode(new DOMElement('abc'));
        $node->setValue('value');
        $this->assertEquals('value', $node->getValue());
    }

    /** @test */
    public function itReturnsSetValueByConverterIfPresent()
    {
        $node = new XmlReaderNode(new DOMElement('abc'), new XmlReaderNodeConfiguration(null, false, function ($oldValue) {
            return $oldValue.'edited';
        }));
        $node->setValue('value');
        $this->assertEquals('valueedited', $node->getValue());
    }

    /** @test */
    public function itReturnsNodeAttributesAsKeyValue()
    {
        $dom = new DOMDocument;
        $el = $dom->createElement('name');
        $el->appendChild(new DOMAttr('key', 'value'));
        $node = new XmlReaderNode($el);
        $attr = $node->getAttributes();
        $this->assertEquals('value', $attr['key']);
    }

    /** @test */
    public function itCanAddChildren()
    {
        $node = new XmlReaderNode(new DOMElement('abc'));
        $node->addChild(new XmlReaderNode(new DOMElement('def')));
        $this->assertCount(1, $node->getChildren());
        $this->assertCount(1, $node->getChildren()['def']);
    }

    /** @test */
    public function itCanTellWhenItIsALeaf()
    {
        $node = new XmlReaderNode(new DOMElement('abc'));
        $this->assertTrue($node->isLeaf());
    }
}
