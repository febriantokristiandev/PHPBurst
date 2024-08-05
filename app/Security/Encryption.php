<?php

namespace App\Security;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class Encryption
{
    private $key;

    public function __construct()
    {
        $this->key = Key::loadFromAsciiSafeString(getenv('ENCRYPTION_KEY'));
    }

    public function encrypt($data)
    {
        return Crypto::encrypt($data, $this->key);
    }

    public function decrypt($data)
    {
        return Crypto::decrypt($data, $this->key);
    }
}
