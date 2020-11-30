<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Keywords\NamespaceKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\NamepsaceKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class NamepsaceKeywordTest extends TestCase
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
        $value = 'o';
        $keyword = new NamespaceKeyword([
            'o' => 'http://example.com'
        ]);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:o=\"http://example.com\"/>", $xml);
    }

    /** @test */
    public function itCanSetMultipleNamespaces()
    {
        $value = ['o', 'p'];
        $keyword = new NamespaceKeyword([
            'o' => 'http://example.com',
            'p' => 'http://second.com'
        ]);
        $keyword->handle($this->document, $this->node, $value);
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Namespace xmlns:o=\"http://example.com\" xmlns:p=\"http://second.com\"/>", $xml);
    }
}
