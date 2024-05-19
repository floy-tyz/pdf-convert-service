<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

class BusinessException extends RuntimeException
{
    /** @var array */
    private $errors;

    public function __construct($message = "", $code = 200, array $errors = [], Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}