<?php

namespace Transip\Api\Client\HttpClient;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Transip\Api\Client\FilesystemAdapter;
use Transip\Api\Client\Repository\AuthRepository;
use Transip\Api\Client\TransipAPI;

abstract class HttpClient
{
    public const TOKEN_CACHE_KEY = 'token';
    public const KEY_FINGERPRINT_CACHE_KEY = 'key-fingerprint';

    /**
     * @var AuthRepository $authRepository
     */
    protected $authRepository;

    /**
     * @var string $endpoint
     */
    protected $endpoint;

    /**
     * @var string $token
     */
    protected $token = '';

    /**
     * @var string $login
     */
    protected $login = '';

    /**
     * @var string $privateKey
     */
    protected $privateKey = '';

    /**
     * @var bool $generateWhitelistOnlyTokens
     */
    protected $generateWhitelistOnlyTokens = false;

    /**
     * @var AdapterInterface
     */
    protected $cache;

    public function __construct(HttpClientInterface $httpClient, string $endpoint)
    {
        $endpoint             = rtrim($endpoint, '/');
        $this->endpoint       = $endpoint;
        $this->authRepository = new AuthRepository($httpClient);
    }

    public function setCache(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function checkAndRenewToken(): void
    {
        $expirationTime = $this->authRepository->getExpirationTimeFromToken($this->token);
        if ($expirationTime <= (time() - 2)) {
            $token = $this->authRepository->createToken($this->login, $this->privateKey, $this->generateWhitelistOnlyTokens);
            $this->setToken($token);

            // Save new token to cache
            $cacheItem = $this->cache->getItem(self::TOKEN_CACHE_KEY);
            $cacheItem->set($token);
            $cacheItem->expiresAfter($this->authRepository->getExpiryTime());
            $this->cache->save($cacheItem);
            // Save private key fingerprint to cache
            $cacheItem = $this->cache->getItem(self::KEY_FINGERPRINT_CACHE_KEY);
            $cacheItem->set($this->getFingerPrintFromKey($this->privateKey));
            $cacheItem->expiresAfter($this->authRepository->getExpiryTime());
            $this->cache->save($cacheItem);
        }
    }

    public function getTokenFromCache()
    {
        $cachedToken = $this->cache->getItem(self::TOKEN_CACHE_KEY);
        $cachedKeyFP = $this->cache->getItem(self::KEY_FINGERPRINT_CACHE_KEY);

        if ($cachedToken->isHit() && $cachedKeyFP->isHit()) {
            $storedKeyFP = $cachedKeyFP->get();
            $storedToken = $cachedToken->get();

            // check if the used private key is still the same, else invalidate the cache
            if ($this->getFingerPrintFromKey($this->privateKey) === $storedKeyFP) {
                $this->setToken($storedToken);
            } else {
                $this->clearCache();
            }
        }
    }

    private function getFingerPrintFromKey(string $privateKey): string
    {
        return hash('SHA512', $privateKey);
    }

    public function clearCache(): void
    {
        $this->cache->deleteItem(self::TOKEN_CACHE_KEY);
        $this->cache->deleteItem(self::KEY_FINGERPRINT_CACHE_KEY);
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }

    public function setGenerateWhitelistOnlyTokens(bool $generateWhitelistOnlyTokens): void
    {
        $this->generateWhitelistOnlyTokens = $generateWhitelistOnlyTokens;
    }
}