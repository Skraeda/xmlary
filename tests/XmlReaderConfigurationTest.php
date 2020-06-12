<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\XmlReaderConfiguration;

/**
 * Unit tests for \Skraeda\Xmlary\XmlReaderConfiguration
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReaderConfigurationTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlReaderConfiguration));
    }
}
