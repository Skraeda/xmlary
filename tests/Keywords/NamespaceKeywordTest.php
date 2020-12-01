<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Keywords\NamespaceKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\NamespaceKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class NamespaceKeywordTest extends TestCase
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
        $keyword = new NamespaceKeyword([
            'xmlns' => 'http://www.w3.org/2000/xmlns/',
            'e' => 'http://example.com'
        ]);
        $keyword->handle($this->document, $this->node, 'e');
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:e=\"http://example.com\"/>", $xml);
    }

    /** @test */
    public function itCanSetMultipleNamespaces()
    {
        $value = ['o', 'p'];
        $keyword = new NamespaceKeyword([
            'xmlns' => 'http://www.w3.org/2000/xmlns/',
            'o' => 'http://example.com',
            'p' => 'http://second.com'
        ]);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:o=\"http://example.com\" xmlns:p=\"http://second.com\"/>", $xml);
    }

    /** @test */
    public function itCanSetDifferentNamespaceUri()
    {
        $value = ['p', ['value' => 'o', 'namespace' => 'p']];
        $keyword = new NamespaceKeyword([
            'xmlns' => 'http://www.w3.org/2000/xmlns/',
            'o' => 'http://example.com',
            'p' => 'http://second.com'
        ]);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:p=\"http://second.com\" p:o=\"http://example.com\"/>", $xml);
    }
}
