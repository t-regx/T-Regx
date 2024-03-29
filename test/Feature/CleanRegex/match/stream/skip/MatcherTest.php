<?php
namespace Test\Feature\CleanRegex\match\stream\skip;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktracking()
    {
        // when
        $first = $this->backtrackingMatch()->stream()->skip(0)->first();
        // then
        $this->assertSame('123', $first->text());
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeys()
    {
        // when
        $first = $this->backtrackingMatch()->stream()->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeysAssoc()
    {
        // when
        $first = $this->backtrackingMatch()->stream()
            ->toMap(Functions::constant(['key' => 'value']))
            ->skip(0)
            ->keys()
            ->first();
        // then
        $this->assertSame('key', $first);
    }
}
