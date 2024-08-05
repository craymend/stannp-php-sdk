<?php

namespace Craymend\Stannp;

/**
 * Return data or errors from API calls
 */
final class Response
{
    /**
     * @var bool
     */
    public $success;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $errors = [];

    /**
     * Contructor
     */
    public function __construct($success = true, array $response = [], array $errors = [])
    {
        $this->success = $success;

        if ($success) {
            $this->data = $response;
        } else {
            $this->errors = $errors;
        }
    }
}