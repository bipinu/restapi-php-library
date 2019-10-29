<?php

namespace Transip\Api\Client\Entity\Vps;

use Transip\Api\Client\Entity\AbstractEntity;

class IpAddress extends AbstractEntity
{
    /**
     * @var string $address
     */
    public $address;

    /**
     * @var string $subnetMask
     */
    public $subnetMask;

    /**
     * @var string $gateway
     */
    public $gateway;

    /**
     * @var string[] $dnsResolvers
     */
    public $dnsResolvers;

    /**
     * @var string $reverseDns
     */
    public $reverseDns;

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getSubnetMask(): string
    {
        return $this->subnetMask;
    }

    public function getGateway(): string
    {
        return $this->gateway;
    }

    /**
     * @return string[]
     */
    public function getDnsResolvers(): array
    {
        return $this->dnsResolvers;
    }

    public function getReverseDns(): string
    {
        return $this->reverseDns;
    }
}
