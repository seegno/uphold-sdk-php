<?php

namespace Bitreserve\Exception;

/**
 * ApiLimitExceedException.
 */
class ApiLimitExceedException extends RuntimeException
{
    public function __construct($rateLimit, $code = 0, $previous = null)
    {
        parent::__construct(sprintf('You have reached Bitreserve API limit! API limit is: %s. Your remaining requests will be reset at %s.', $rateLimit['limit'], $rateLimit['reset']), $code, $previous);
    }
}
