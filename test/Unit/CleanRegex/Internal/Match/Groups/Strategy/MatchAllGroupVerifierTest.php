<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Groups\Strategy;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Groups\Strategy\MatchAllGroupVerifier;

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
        $verifier = new MatchAllGroupVerifier(Internal::pcre($pattern));

        // when
        $results = $verifier->groupExists('group');

        // then
        $this->assertSame($expected, $results);
    }

    public function existingGroupsAndVerifiers(): array
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
