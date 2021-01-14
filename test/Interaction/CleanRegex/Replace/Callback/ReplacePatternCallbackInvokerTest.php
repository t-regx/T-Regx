<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\Callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

class ReplacePatternCallbackInvokerTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldInvokeCallback()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 2, new DefaultStrategy(), new IgnoreCounting());
        $callback = function (ReplaceDetail $detail) {
            return "*" . ($detail->toInt() + 1) . '*';
        };

        // when
        $result = $invoker->invoke($callback, new MatchStrategy());

        // then
        $this->assertSame('Tom Cruise is *22* years old and has *193*cm', $result);
    }

    /**
     * @test
     */
    public function shouldPassOffsets()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 2, new DefaultStrategy(), new IgnoreCounting());
        $offsets = [];
        $callback = function (ReplaceDetail $detail) use (&$offsets) {
            $offsets[] = $detail->offset();
            return (string)$detail;
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());

        // then
        $this->assertSame([14, 35], $offsets);
    }

    /**
     * @test
     */
    public function shouldInvokeUpToLimit()
    {
        // given
        $subject = '192.168.17.20';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 3, new DefaultStrategy(), new IgnoreCounting());
        $values = [];
        $callback = function (ReplaceDetail $detail) use (&$values) {
            $values[] = $detail;
            return '';
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());

        // then
        $this->assertSameMatches(['192', '168', '17'], $values);
    }

    /**
     * @test
     */
    public function shouldSliceAllUpToLimit()
    {
        // given
        $subject = '192.168.17.20';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 3, new DefaultStrategy(), new IgnoreCounting());
        $callback = function (ReplaceDetail $detail) {
            // then
            $this->assertSame(['192', '168', '17', '20'], $detail->all());

            return '';
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());
    }

    /**
     * @test
     */
    public function shouldCreateDetailObjectWithSubject()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 2, new DefaultStrategy(), new IgnoreCounting());
        $callback = function (ReplaceDetail $detail) use ($subject) {
            // then
            $this->assertSame($subject, $detail->subject());

            return '';
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());
    }

    /**
     * @test
     */
    public function shouldNotInvokeCallback_limit_0()
    {
        // given
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::pcre('//'), new Subject(''), 0, new DefaultStrategy(), new IgnoreCounting());

        // when
        $result = $invoker->invoke([$this, 'fail'], new MatchStrategy());

        // then
        $this->assertSame('', $result);
    }
}
