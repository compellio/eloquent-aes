<?php


namespace Compellio\EloquentAES\Contracts;

use Compellio\EloquentAES\Exceptions\AESKeyMissing;

interface AESKeyHandler
{
    /**
     * Has the key been generated
     *
     * @return bool
     */
    public function exists();

    /**
     * Save the generated key to the storage location
     *
     * @param $public
     * @param $private
     */
    public function saveKey($key);

    /**
     * Get key string
     *
     * @return string
     * @throws AESKeyMissing
     */
    public function getKey();
}
