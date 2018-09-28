<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Groups\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;

class GroupVerifierTest extends TestCase
{
    /**
     * @test
     * @dataProvider existingGroupsAndVerifiers
     * @param string $pattern
     * @param bool $expected
     */
    public function shouldStrategiesAgreeWithEachOther(string $pattern, bool $expected)
    {
        // given
        $strategies = [
            'match' => new MatchAllGroupVerifier()
        ];

        // when
        $results = $this->getStrategiesResults($strategies, $pattern);

        // then
        $this->assertEquals(['match' => $expected], $results);
    }

    private function getStrategiesResults(array $verifiers, string $pattern): array
    {
        $results = [];
        foreach ($verifiers as $key => $verifier) {
            $results[$key] = $verifier->groupExists($pattern, 'group');
        }
        return $results;
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
