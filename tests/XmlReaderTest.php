<?php

namespace Skraeda\Xmlary\Tests;

use DOMAttr;
use Mockery;
use PHPUnit\Framework\TestCase;
use Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract;
use Skraeda\Xmlary\XmlReader;
use Skraeda\Xmlary\XmlReaderConfiguration;
use Skraeda\Xmlary\XmlReaderNodeConfiguration;

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

    /**
     * @test
     * @dataProvider xmlProvider
     **/
    public function itCanParseXmlToArray($xml, $config, $expectedOutput)
    {
        $reader = new XmlReader(new XmlReaderConfiguration);
        $result = $reader->parse($xml, $config);
        $this->assertEquals($expectedOutput, $result);
    }

    /**
     * Provides XML test data.
     *
     * @return array
     */
    public function xmlProvider(): array
    {
        return [
            ['<Root />', [], ['Root' => ['@value' => null]]],
            ['<Root attr="foo" />', [], ['Root' => ['@value' => null, '@attributes' => ['attr' => 'foo']]]],
            ['<Root><Single>val</Single></Root>', [], ['Root' => ['Single' => ['@value' => 'val']]]],
            ['<Root><Double>v</Double><Double>v2</Double></Root>', [], ['Root' => ['Double' => [['@value' => 'v'], ['@value' => 'v2']]]]],
            ['<Root><A><B><C>D</C></B></A></Root>', [], ['Root' => ['A' => ['B' => ['C' => ['@value' => 'D']]]]]],
            ['<Root><![CDATA[value]]></Root>', [], ['Root' => ['@value' => 'value']]],
            ['<Root><A>B</A></Root>', [
                'Root' => [
                    '@config' => new XmlReaderNodeConfiguration('NewRoot'),
                    'A' => [
                        '@config' => new XmlReaderNodeConfiguration('Element', true, function () {
                            return 'C';
                        }, function ($node) {
                            $node->getNode()->appendChild(new DOMAttr('key', 'value'));
                        })
                    ]
                ]
            ], [
                'NewRoot' => [
                    'Element' => [
                        [
                            '@attributes' => [
                                'key' => 'value'
                            ],
                            '@value' => 'C'
                        ]
                    ]
                ]
            ]]
        ];
    }
}
