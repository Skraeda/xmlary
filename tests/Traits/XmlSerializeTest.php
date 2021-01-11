<?php

namespace Skraeda\Xmlary\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlSerializable;
use Skraeda\Xmlary\Traits\XmlSerialize;

/**
 * Unit tests for \Skraeda\Xmlary\Traits\XmlSerialize
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlSerializeTest extends TestCase
{
    /** @test */
    public function itCanSerializePrimitives()
    {
        $o = new class {
            use XmlSerialize;

            protected $number = 1;
            protected $text = 'text';
            protected $flag = false;
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals(1, $arr['number']);
        $this->assertEquals('text', $arr['text']);
        $this->assertEquals(false, $arr['flag']);
    }

    /** @test */
    public function itCanSerializeArrays()
    {
        $o = new class {
            use XmlSerialize;

            protected $arr;

            public function __construct()
            {
                $this->arr = [1, 2, 3];
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertCount(3, $arr['arr']);
    }

    /** @test */
    public function itCanSerializeXmlSerializables()
    {
        $nested = new class implements XmlSerializable {
            public function xmlSerialize(): array
            {
                return ['value' => 'foo'];
            }
        };

        $o = new class($nested) {
            use XmlSerialize;

            protected $nested;

            public function __construct($nested)
            {
                $this->nested = $nested;
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals('foo', $arr['nested']['value']);
    }

    /** @test */
    public function itCanSerializeNoProps()
    {
        $o = new class {
            use XmlSerialize;
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEmpty($arr);
    }

    /** @test */
    public function itCanSerializeWithAttributes()
    {
        $o = new class {
            use XmlSerialize;

            protected $el = 'value';

            protected function xmlSerializeAttributes(?string $prop = null): array
            {
                return [
                    'foo' => 'bar'
                ];
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals('value', $arr['el']['@value']);
        $this->assertEquals('bar', $arr['el']['@attributes']['foo']);
    }

    /** @test */
    public function itCanAddNamespacePrefix()
    {
        $o = new class {
            use XmlSerialize;

            protected $el = 'value';

            protected function xmlSerializeNamespacePrefix(): string
            {
                return 'foo';
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals('value', $arr['foo:el']);
    }

    /** @test */
    public function itCanChangeTag()
    {
        $o = new class {
            use XmlSerialize;

            protected $el = 'value';

            protected function xmlSerializePropToTag(string $prop): string
            {
                return 'ELEMENT';
            }
        };
        $arr = $o->xmlSerialize()[get_class($o)];
        $this->assertEquals('value', $arr['ELEMENT']);
    }

    /** @test */
    public function itCanSpecifyANewRootName()
    {
        $o = new class {
            use XmlSerialize;

            protected $el = 'value';

            protected function xmlSerializeRootName(): ?string
            {
                return 'Foo';
            }
        };
        $arr = $o->xmlSerialize()['Foo'];
        $this->assertEquals('value', $arr['el']);
    }

    /** @test */
    public function itCanSpecifyNullRootName()
    {
        $o = new class {
            use XmlSerialize;

            protected $el = 'value';

            protected function xmlSerializeRootName(): ?string
            {
                return null;
            }
        };
        $this->assertEquals('value', $o->xmlSerialize()['el']);
    }
}
