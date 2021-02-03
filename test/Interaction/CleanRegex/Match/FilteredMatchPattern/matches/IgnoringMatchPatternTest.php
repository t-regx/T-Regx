<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\FilteredMatchPattern\matches;

use PHPUnit\Framework\TestCase;
use Test\Utils\CallbackPredicate;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\IgnoreBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\IgnoringMatchPattern;

class IgnoringMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_allMatch();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldMatch_some()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldNotMatch_notMatched()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notMatches();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertFalse($matches);
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldNotMatch_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertFalse($matches);
        $this->assertTrue($fails);
    }

    private function standardMatchPattern(): IgnoringMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Detail $detail) {
            return $detail->index() != 1;
        });
    }

    private function standardMatchPattern_allMatch(): IgnoringMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::constant(true));
    }

    private function standardMatchPattern_notMatches(): IgnoringMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'NOT MATCHING', Functions::constant(true));
    }

    private function standardMatchPattern_filtered(): IgnoringMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern long', Functions::constant(false));
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): IgnoringMatchPattern
    {
        return new IgnoringMatchPattern(new IgnoreBaseDecorator(
            new ApiBase(InternalPattern::standard($pattern), $subject, new UserData()),
            new CallbackPredicate($predicate)));
    }
}
