<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\XmlElement;

/**
 * Unit tests for \Skraeda\Xmlary\XmlElement
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlElementTest extends TestCase
{
    /** @test */
    public function isThereASyntaxError()
    {
        $o = new class extends XmlElement {
        };

        $this->assertTrue(is_object($o));
    }

    /** @test */
    public function itSerializesWithoutRootElement()
    {
        $o = new class extends XmlElement {
            protected $number = 1;
            protected $text = 'text';
            protected $flag = false;
        };
        $arr = $o->xmlSerialize();
        $this->assertEquals(1, $arr['number']);
        $this->assertEquals('text', $arr['text']);
        $this->assertEquals(false, $arr['flag']);
    }
}
