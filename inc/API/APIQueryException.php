<?php

namespace SWPER\GitHubRepoLiveSearch\API;

defined('ABSPATH') or die();

/**
 * Classe com uma implementação inicial de exceções personalizadas
 * Author: Claudio Hessel 2021
 */
class APIQueryException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function recoveryFunction()
    {
    }
}
