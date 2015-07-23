<?php

namespace Bitreserve\Factory;

use Bitreserve\BitreserveClient;

/**
 * BitreserveClient factory.
 */
class BitreserveClientFactory
{
    /**
     * Create a new `BitreserveClient`.
     *
     * @param array $options An array of BitreserveClient's options.
     *
     * @return BitreserveClient
     */
    public function create(array $options = array())
    {
        return new BitreserveClient($options);
    }
}
