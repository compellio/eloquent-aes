<?php


namespace RichardStyles\EloquentAES\Exceptions;


class AESKeyMissing extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Eloquent Encryption AES key cannot be found.';
}
