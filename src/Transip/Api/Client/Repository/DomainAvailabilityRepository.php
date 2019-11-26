<?php

namespace Transip\Api\Client\Repository;

use Transip\Api\Client\Entity\DomainCheckResult;

class DomainAvailabilityRepository extends ApiRepository
{
    protected function getRepositoryResourceNames(): array
    {
        return ['domain-availability'];
    }

    public function checkDomainName(string $domainName): DomainCheckResult
    {
        $response          = $this->httpClient->get($this->getResourceUrl($domainName));
        $domainCheckResult = $response['availability'] ?? null;
        return new DomainCheckResult($domainCheckResult);
    }
}
