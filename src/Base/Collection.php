<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Concerns\Request\Multipart;
use Laravie\Codex\Contracts\Response as ResponseContract;

abstract class Collection extends Request
{
    use Multipart;

    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(string $title, array $optional = []): ResponseContract
    {
        $files = [];
        $body = array_merge(compact('title'), $optional);

        if (isset($body['logo'])) {
            $files['logo'] = ltrim($body['logo'], '@');
            unset($body['logo']);
        }

        list($headers, $stream) = $this->prepareMultipartRequestPayloads([], $body, $files);

        return $this->send('POST', 'collections', $headers, $stream);
    }

    /**
     * Get collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $id): ResponseContract
    {
        return $this->send('GET', "collections/{$id}", [], []);
    }

    /**
     * Get collection index.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function index(array $optional = []): ResponseContract
    {
        return $this->send('GET', 'collections', [], $optional);
    }

    /**
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function createOpen(
        string $title,
        string $description,
        $amount,
        array $optional = []
    ): ResponseContract {
        return $this->client->uses('Collection.Open', $this->getVersion())
                    ->create($title, $description, $amount, $optional);
    }

    /**
     * Get open collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function getOpen(string $id): ResponseContract
    {
        return $this->client->uses('Collection.Open', $this->getVersion())
                    ->get($id);
    }

    /**
     * Get open collection index.
     *
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function indexOpen(array $optional = []): ResponseContract
    {
        return $this->client->uses('Collection.Open', $this->getVersion())
                    ->index($optional);
    }

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function activate(string $id): ResponseContract
    {
        return $this->send('POST', "collections/{$id}/activate", [], []);
    }

    /**
     * Deactivate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function deactivate(string $id): ResponseContract
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }
}
