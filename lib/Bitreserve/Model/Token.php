<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;
use Bitreserve\Exception\AuthenticationRequiredException;

/**
 * Token Model.
 */
class Token extends BaseModel implements TokenInterface
{
    /**
     * Constructor.
     *
     * @param BitreserveClient $client Bitreserve client.
     */
    public function __construct(BitreserveClient $client)
    {
        if (!$client->getOption('bearer')) {
            throw new AuthenticationRequiredException('Missing bearer authorization');
        }

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        $data = $this->client->get('/me');

        return new User($this->client, $data);
    }
}
