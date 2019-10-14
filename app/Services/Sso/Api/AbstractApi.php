<?php

namespace App\Services\Sso\Api;

use App\Services\Sso\Adapter\AdapterInterface;

abstract class AbstractApi
{
    /** @var AdapterInterface */
    protected $adapter;

    /** @var string */
    protected $endpoint;

    /**
     * AbstractApi constructor
     *
     * @param AdapterInterface $adapter
     * @param null $endpoint
     */
    public function __construct(AdapterInterface $adapter, $endpoint = null)
    {
        $this->adapter = $adapter;
        $this->endpoint = $endpoint ?: config('sso.base_url');
    }
}