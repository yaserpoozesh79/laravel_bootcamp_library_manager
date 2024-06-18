<?php

namespace Yaser\Libmanager;

class User
{

    protected string $password;

    public function __construct(
        protected string $username,
        string $password,
        protected string $firstName,
        protected string $lastName,
        protected string $nationalCode
    )
    {
        $this->password = $this->encrypt($password);
    }

    private function encrypt(string $password):string
    {
        return sodium_crypto_pwhash(
            30,
            $password,
            "173fs7392nd83x4f",
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
        );

    }

    public function getFirstName():string
    {
        return $this->firstName;
    }

    public function getLastName():string
    {
        return $this->lastName;
    }

    public function getNationalCode():string
    {
        return $this->nationalCode;
    }

    public function setFirstName($firstName):void
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName):void
    {
        $this->lastName = $lastName;
    }

    public function setNationalCode($nationalCode):void
    {
        $this->nationalCode = $nationalCode;
    }

    public function getUsername():string
    {
        return $this->username;
    }

    public function checkPassword(string $password):bool
    {
        return $this->password == $this->encrypt($password);
    }
}