<?php

namespace App\Services\Sso\Adapter;

interface AdapterInterface
{
    /**
     * Get
     *
     * @param $url
     * @return mixed
     */
    public function get($url);

    /**
     * Post
     *
     * @param $url
     * @param array $contents
     * @return mixed
     */
    public function post($url, array $contents = []);

    /**
     * Put
     *
     * @param $url
     * @param array $contents
     * @return mixed
     */
    public function put($url, array $contents = []);

    /**
     * Patch
     *
     * @param $url
     * @param array $contents
     * @return mixed
     */
    public function patch($url, array $contents = []);

    /**
     * Delete
     *
     * @param $url
     * @return mixed
     */
    public function delete($url);
}