<?php

namespace Skraeda\Xmlary\Tests;

use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\XmlWriterConfiguration;

/**
 * Unit tests for \Skraeda\Xmlary\XmlWriterConfiguration
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlWriterConfigurationTest extends TestCase
{
    /** @test */
    public function isThereAnySyntaxError()
    {
        $this->assertTrue(is_object(new XmlWriterConfiguration));
    }
}
