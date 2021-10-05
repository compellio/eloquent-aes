<?php

return [

    'key' => env('ELOQUENT_KEY'),

    'cipher' => 'AES-256-CBC',

    /**
     * This class can be overridden to define how the key is stored, checked for
     * existence and returned for Encryption and Decryption. This allows for keys to
     * be held in secure Vaults or through another provider.
     */
    'handler' => \RichardStyles\EloquentAES\FileSystem\AESKeyStorageHandler::class,

];