<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getJsonAs(string $uri, $account, $headers = []): TestResponse
    {
        $headers = array_merge(
            $headers,
            ['Authorization' => "Bearer {$account->developer_key}"]
        );

        return $this->getJson($uri, $headers);
    }

    protected function postJsonAs(string $uri, $account, $data = [], $headers = []): TestResponse
    {
        $headers = array_merge(
            $headers,
            ['Authorization' => "Bearer {$account->developer_key}"]
        );

        return $this->postJson($uri, $data, $headers);
    }

    protected function deleteJsonAs(string $uri, $account, $data = [], $headers = []): TestResponse
    {
        $headers = array_merge(
            $headers,
            ['Authorization' => "Bearer {$account->developer_key}"]
        );

        return $this->deleteJson($uri, $data, $headers);
    }
}
