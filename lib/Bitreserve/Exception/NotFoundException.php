<?php

namespace Bitreserve\Exception;

/**
 * NotFoundException.
 */
class NotFoundException extends ErrorException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct('Object or route not found!', $code, $previous);
    }
}
