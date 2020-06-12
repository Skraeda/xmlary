<?php

namespace Skraeda\Xmlary\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;
use Skraeda\Xmlary\XmlReaderNode;
use Skraeda\Xmlary\XmlReaderNodeConfiguration as Config;

/**
 * Unit tests for \Skraeda\Xmlary\XmlReaderNodeConfiguration
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderNodeConfigurationTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new Config));
    }

    /** @test */
    public function itReturnsNewNameIfPresent()
    {
        $conf = new Config('newname');
        $this->assertEquals('newname', $conf->rename('banani'));
    }

    /** @test */
    public function itReturnsOldNameIfNoNewNameIsPresent()
    {
        $conf = new Config;
        $this->assertEquals('banani', $conf->rename('banani'));
    }

    /** @test */
    public function itReturnsArrayFlagAsFalseByDefault()
    {
        $conf = new Config;
        $this->assertFalse($conf->isArray());
    }

    /** @test */
    public function itCanConvertValuesUsingCallable()
    {
        $conf = new Config(null, false, function ($oldValue) {
            return 'newValue';
        });
        $this->assertEquals('newValue', $conf->convert('foo'));
    }

    /** @test */
    public function itCanConvertValuesUsingXmlValueConverter()
    {
        $conf = new Config(null, false, new class implements XmlValueConverterContract {
            public function convert($value)
            {
                return 'newValue';
            }
        });

        $this->assertEquals('newValue', $conf->convert('foo'));
    }

    /** @test */
    public function itReturnsOldValueWithNoValidConverter()
    {
        $conf = new Config(null, false, 'foo');
        $this->assertEquals('value', $conf->convert('value'));
    }

    /** @test */
    public function itExecutesCallbackIfItsCallable()
    {
        $check = false;
        $conf = new Config(null, false, null, function ($node) use (&$check) {
            $check = true;
        });
        $conf->callback(Mockery::mock(XmlReaderNode::class));
        $this->assertTrue($check);
    }

    /** @test */
    public function itSilentlyFailsIfCallbackIsInvalid()
    {
        $conf = new Config(null, false, null, 'asdf');
        $this->assertNull($conf->callback(Mockery::mock(XmlReaderNode::class)));
    }
}
