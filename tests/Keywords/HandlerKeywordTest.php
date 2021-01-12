<?php

namespace Skraeda\Xmlary\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Keywords\HandlerKeyword;

/**
 * Unit tests for \Skraeda\Xmlary\Keywords\HandlerKeyword
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class HandlerKeywordTest extends TestCase
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
        $this->node = $this->document->createElement("Handler");
        $this->document->appendChild($this->node);
    }

    /** @test */
    public function itExecutesHandler()
    {
        $keyword = new HandlerKeyword;
        $keyword->handle($this->document, $this->node, function ($doc, $parent) {
            $parent->appendChild($doc->createElement('Handled', 'Value'));
        });
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Handler><Handled>Value</Handled></Handler>", $xml);
    }

    /** @test */
    public function itIgnoresNonCallables()
    {
        $keyword = new HandlerKeyword;
        $keyword->handle($this->document, $this->node, 'foo');
        $xml = $this->document->saveXML($this->document->documentElement);
        $this->assertEquals("<Handler/>", $xml);
    }
}
