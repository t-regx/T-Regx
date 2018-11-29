<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Groups\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;

class MatchAllGroupVerifierTest extends TestCase
{
    /**
     * @test
     * @dataProvider existingGroupsAndVerifiers
     * @param string $pattern
     * @param bool $expected
     */
    public function shouldVerify(string $pattern, bool $expected)
    {
        // given
        $verifier = new MatchAllGroupVerifier(new InternalPattern($pattern));

        // when
        $results = $verifier->groupExists('group');

        // then
        $this->assertEquals($expected, $results);
    }

    public function existingGroupsAndVerifiers()
    {
        return [
            ['/ab (?<group>c)/', true],
            ['/ab (?P<group>c)/', true],
            ["/ab (?'group'c)/", true],
            ["/ab (?'group'c (?<P>xd))/", true],

            ['/ab \(?<group>c\)/', false],
            ['/ab \(?P<group>c\)/', false],
            ["/ab \(?'group'c\)/", false],
        ];
    }
}
