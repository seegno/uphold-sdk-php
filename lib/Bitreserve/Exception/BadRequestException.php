<?php

namespace Bitreserve\Exception;

/**
 * BadRequestException.
 */
class BadRequestException extends ErrorException
{
    public function __construct($errors, $code = 0, $previous = null)
    {
        $message = sprintf('The response returned a "%s" error.', $errors['code']);

        if (isset($errors['errors'])) {
            $message = sprintf("%s\nError List:\n%s", $message, print_r($errors['errors'], 1));
        } elseif (isset($errors['error_description'])) {
            $message = sprintf("%s Error description: %s", $message, $errors['error_description']);
        }

        parent::__construct($message, $code, $previous);
    }
}
