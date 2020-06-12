<?php

namespace Skraeda\Xmlary\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract;
use Skraeda\Xmlary\XmlReader;
use Skraeda\Xmlary\XmlReaderConfiguration;

/**
 * Unit tests for \Skraeda\Xmlary\XmlReader
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderTest extends TestCase
{
    /**
     * XML writer config mock.
     *
     * @var \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract
     */
    protected $config;

    /**
     * Setup the test cases.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->config = Mockery::mock(XmlReaderConfigurationContract::class);
    }

    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlReader));
    }

    /** @test */
    public function itCanChangeConfiguration()
    {
        $reader = new XmlReader(new XmlReaderConfiguration);
        $reader->setConfiguration($this->config);
        $this->assertEquals($this->config, $reader->getConfiguration());
    }
}
