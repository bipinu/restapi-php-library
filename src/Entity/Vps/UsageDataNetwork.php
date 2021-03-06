<?php

namespace Transip\Api\Library\Entity\Vps;

class UsageDataNetwork extends UsageData
{
    /**
     * @var float $mbitIn
     */
    public $mbitIn;

    /**
     * @var float $mbitOut
     */
    public $mbitOut;

    public function getMbitIn(): float
    {
        return $this->mbitIn;
    }

    public function getMbitOut(): float
    {
        return $this->mbitOut;
    }
}
