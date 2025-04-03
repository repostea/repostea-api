<?php

namespace Tests\Traits;

trait WithApiKeyHeaders
{
    protected function apiHeaders(array $headers = []): array
    {
        return array_merge($headers, [
            'X-Repostea-Id' => $this->tenant->uuid,
            'X-Repostea-Api-Key' => $this->tenant->api_key,
        ]);
    }

    protected function postJsonWithKey(string $uri, array $data = [], array $headers = [])
    {
        return $this->postJson($uri, $data, $this->apiHeaders($headers));
    }

    protected function getJsonWithKey(string $uri, array $headers = [])
    {
        return $this->getJson($uri, $this->apiHeaders($headers));
    }

    protected function withAuthHeaders(string $token, array $headers = []): array
    {
        return $this->apiHeaders(array_merge($headers, [
            'Authorization' => 'Bearer '.$token,
        ]));
    }
}
