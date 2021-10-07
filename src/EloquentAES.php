<?php

namespace Compellio\EloquentAES;

use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Compellio\EloquentAES\Contracts\AESKeyHandler;
use Compellio\EloquentAES\Exceptions\InvalidAESKeyHandler;
use Compellio\EloquentAES\FileSystem\AESKeyStorageHandler;

class EloquentAES implements EncrypterContract
{
    /**
     * @var AESKeyHandler
     */
    private $handler;

    /**
     * ApplicationKey constructor.
     */
    public function __construct()
    {
        $this->handler = app()->make(
            Config::get('eloquentaes.handler', AESKeyStorageHandler::class)
        );

        if(!$this->handler instanceof AESKeyHandler){
            throw new InvalidAESKeyHandler;
        }
    }

    /**
     * Check if a Key exists.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->handler->exists();
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    public function generateRandomKey()
    {
        $key = 'base64:'.base64_encode(
                Encrypter::generateKey($this->getCipher())
            );

        $this->handler->saveKey($key);
    }

    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     *
     * @throws \Illuminate\Contracts\Encryption\EncryptException
     */
    public function encrypt($value, $serialize = false)
    {
        $encrypter = new Encrypter($this->parseKey(), $this->getCipher());

        return $encrypter->encrypt($value, $serialize);
    }

    /**
     * Decrypt the given value.
     *
     * @param  string  $payload
     * @param  bool  $unserialize
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     */
    public function decrypt($value, $unserialize = false)
    {
        $encrypter = new Encrypter($this->parseKey(), $this->getCipher());

        return $encrypter->decrypt($value, $unserialize);
    }

    /**
     * Get the cipher from config.
     *
     * @return string
     */
    protected function getCipher()
    {
        return Config::get('eloquentaes.cipher', 'AES-256-CBC');
    }

    /**
     * Parse the encryption key.
     *
     * @return string
     */
    protected function parseKey()
    {
        if (Str::startsWith($key = $this->handler->getKey(), $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }
}