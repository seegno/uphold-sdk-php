<?php

namespace Uphold\Factory;

use Uphold\UpholdClient;

/**
 * UpholdClient factory.
 */
class UpholdClientFactory
{
    /**
     * Create a new `UpholdClient`.
     *
     * @param array $options An array of UpholdClient's options.
     *
     * @return UpholdClient
     */
    public function create(array $options = array())
    {
        return new UpholdClient($options);
    }
}
