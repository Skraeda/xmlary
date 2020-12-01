<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Keywords\DefineKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\DefineKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class DefineKeywordTest extends TestCase
{
    /**
     * DOM Document.
     *
     * @var \DOMDocument
     */
    protected $document;

    /**
     * DOM element parent.
     *
     * @var \DOMElement
     */
    protected $node;

    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->document = new DOMDocument;
        $this->node = $this->document->createElement("Namespace");
        $this->document->appendChild($this->node);
    }

    /** @test */
    public function itCanSetSingleNamespace()
    {
        $keyword = new DefineKeyword([
            'http://example.com' => 'e'
        ]);
        $keyword->handle($this->document, $this->node, 'http://example.com');
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:e=\"http://example.com\"/>", $xml);
    }

    /** @test */
    public function itCanSetMultipleNamespaces()
    {
        $value = ['http://example.com', 'http://second.com'];
        $keyword = new DefineKeyword([
            'http://example.com' => 'o',
            'http://second.com' => 'p'
        ]);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:o=\"http://example.com\" xmlns:p=\"http://second.com\"/>", $xml);
    }

    /** @test */
    public function itCanSetDifferentNamespaceUri()
    {
        $value = ['http://second.com', ['value' => 'http://example.com', 'namespace' => 'http://second.com']];
        $keyword = new DefineKeyword([
            'http://example.com' => 'o',
            'http://second.com' => 'p'
        ]);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:p=\"http://second.com\" p:o=\"http://example.com\"/>", $xml);
    }
}
