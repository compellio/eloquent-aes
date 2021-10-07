<?php


namespace Compellio\EloquentAES\Exceptions;


class InvalidAESKeyHandler extends \Exception
{
    /**
     * @var string
     */
    protected $message = "Invalid Handler class. The AES Key Handler must implement AESKeyHandler";
}
