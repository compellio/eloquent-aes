<?php

namespace Compellio\EloquentAES\FileSystem;

use Illuminate\Support\Facades\Config;
use Compellio\EloquentAES\Contracts\AESKeyHandler;
use Compellio\EloquentAES\Exceptions\AESKeyMissing;

class AESKeyStorageHandler implements AESKeyHandler
{
    /**
     * Has the key been generated
     *
     * @return bool
     */
    public function exists()
    {
        $currentKey = $this->envKeyPath();

        if (strlen($currentKey) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Save the generated key to the storage location
     *
     * @param $public
     * @param $private
     */
    public function saveKey($key)
    {
        if($this->environmentFileWithExists()) {
            file_put_contents(app()->environmentFilePath(), preg_replace(
                $this->keyReplacementPattern(),
                'ELOQUENT_KEY=' . $key,
                file_get_contents(app()->environmentFilePath())
            ));

            return;
        }

        file_put_contents(app()->environmentFilePath(),
            file_get_contents(app()->environmentFilePath()) . PHP_EOL .
            '# You should backup this key in a safe secure place' . PHP_EOL .
            'ELOQUENT_KEY=' . $key . PHP_EOL
        );
    }

    /**
     * Get key string
     *
     * @return string
     * @throws AESKeyMissing
     */
    public function getKey()
    {
        if (!$this->exists()) {
            throw new AESKeyMissing();
        }

        return $this->envKeyPath();
    }

    protected function environmentFileWithExists()
    {
        return preg_match(
            $this->keyReplacementPattern(),
            file_get_contents(app()->environmentFilePath())
        );
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->envKeyPath(), '/');

        return "/^ELOQUENT_KEY{$escaped}/m";
    }

    /**
     * Get the key path in .env.
     *
     * @return string
     */
    protected function envKeyPath()
    {
        return Config::get('eloquentaes.key', '');
    }
}
