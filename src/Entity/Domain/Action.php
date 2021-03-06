<?php

namespace Transip\Api\Library\Entity\Domain;

use Transip\Api\Library\Entity\AbstractEntity;

class Action extends AbstractEntity
{
    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $message
     */
    public $message;

    /**
     * @var bool $hasFailed
     */
    public $hasFailed;

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getHasFailed(): bool
    {
        return $this->hasFailed;
    }
}
