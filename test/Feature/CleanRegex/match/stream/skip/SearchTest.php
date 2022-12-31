<?php
namespace Test\Feature\CleanRegex\match\stream\skip;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktracking()
    {
        // when
        $first = $this->backtrackingSearch()->stream()->skip(0)->first();
        // then
        $this->assertSame('123', $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeys()
    {
        // when
        $first = $this->backtrackingSearch()->stream()->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeysAssoc()
    {
        // when
        $first = $this->backtrackingSearch()->stream()
            ->toMap(Functions::constant(['key' => 'value']))
            ->skip(0)
            ->keys()
            ->first();
        // then
        $this->assertSame('key', $first);
    }
}
