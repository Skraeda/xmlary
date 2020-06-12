<?php

namespace Skraeda\Xmlary;

use Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract;
use Skraeda\Xmlary\Contracts\XmlReaderContract;

/**
 * XmlReader implementation.
 *
 * @author Gunnar Örn Baldursson <gunnar@sjukraskra.is>
 */
class XmlReader implements XmlReaderContract
{
    /**
     * XmlReader configuration
     *
     * @var \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract
     */
    protected $config;
    
    /**
     * XmlReader constructor.
     *
     * @param \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract|null $config
     */
    public function __construct(?XmlReaderConfigurationContract $config = null)
    {
        $this->config = $config ?? new XmlReaderConfiguration;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(string $xml, array $mapping = []): array
    {
        return [

        ];
    }

    /**
     * Get Config
     *
     * @return \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract
     */
    public function getConfiguration(): XmlReaderConfigurationContract
    {
        return $this->config;
    }
    
    /**
     * Set Config
     *
     * @param \Skraeda\Xmlary\Contracts\XmlReaderConfigurationContract $config
     * @return self
     */
    public function setConfiguration(XmlReaderConfigurationContract $config): self
    {
        $this->config = $config;
        
        return $this;
    }
}
