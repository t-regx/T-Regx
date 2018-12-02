<?php
namespace TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\OffsetMatchImpl;
use TRegx\CleanRegex\Match\Details\Match;

class UserDataContainerTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $container = new UserData();
        $match = $this->getMatch();
        $otherMatch = $this->getOtherMatch();

        // when
        $container->get($match)->set('value');
        $container->get($otherMatch)->set('value 2');
        $result = $container->get($match)->get();

        // then
        $this->assertEquals('value', $result);
    }

    public function getMatch(): Match
    {
        return new OffsetMatchImpl(14);
    }

    public function getOtherMatch(): Match
    {
        return new OffsetMatchImpl(15);
    }
}
