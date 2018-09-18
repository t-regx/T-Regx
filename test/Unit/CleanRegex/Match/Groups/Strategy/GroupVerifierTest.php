<?php
namespace Test\Unit\CleanRegex\Match\Groups\Strategy;

use CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;
use CleanRegex\Match\Groups\Strategy\PatternAnalyzeGroupVerifier;
use PHPUnit\Framework\TestCase;

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
            'match'   => new MatchAllGroupVerifier(),
            'analyze' => new PatternAnalyzeGroupVerifier()
        ];

        // when
        $results = $this->getStrategiesResults($strategies, $pattern);

        // then
        $this->assertEquals(['match' => $expected, 'analyze' => $expected], $results);
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

            ['/ab \(?<group>c\)/', false],
            ['/ab \(?P<group>c\)/', false],
            ["/ab \(?'group'c\)/", false],
        ];
    }
}
