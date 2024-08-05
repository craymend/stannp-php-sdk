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
     * @var int
     */
    public $statusCode;

    /**
     * @var object
     */
    public $data;

    /**
     * @var string
     */
    public $error;

    /**
     * Contructor
     */
    public function __construct($success = true, int $statusCode = null, object $response = null, string $error = null)
    {
        $this->success = $success;
        $this->statusCode = $statusCode;
        $this->data = $response;
        $this->error = $error;
    }
}