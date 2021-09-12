<?php
namespace Test\Fakes\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Match\UserData;

class UserDataDetail extends ThrowDetail
{
    /** @var UserData */
    private $userData;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
    }

    public function setUserData($userData): void
    {
        $this->userData->set($this, $userData);
    }

    public function getUserData()
    {
        return $this->userData->get($this);
    }

    public function byteOffset(): int
    {
        // this knows about contract between Detail and UserData
        // this method knows it's the key
        return -99;
    }
}
