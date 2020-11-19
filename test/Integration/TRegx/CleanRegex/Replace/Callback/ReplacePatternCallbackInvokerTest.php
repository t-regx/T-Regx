<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\Callback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

class ReplacePatternCallbackInvokerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInvokeCallback()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 2, new DefaultStrategy());
        $callback = function (ReplaceDetail $detail) {
            return "*" . ($detail->toInt() + 1) . '*';
        };

        // when
        $result = $invoker->invoke($callback, new MatchStrategy());

        // then
        $this->assertEquals('Tom Cruise is *22* years old and has *193*cm', $result);
    }

    /**
     * @test
     */
    public function shouldPassOffsets()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 2, new DefaultStrategy());
        $offsets = [];
        $callback = function (ReplaceDetail $detail) use (&$offsets) {
            $offsets[] = $detail->offset();
            return (string)$detail;
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());

        // then
        $this->assertEquals([14, 35], $offsets);
    }

    /**
     * @test
     */
    public function shouldInvokeUpToLimit()
    {
        // given
        $subject = '192.168.17.20';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 3, new DefaultStrategy());
        $values = [];
        $callback = function (ReplaceDetail $detail) use (&$values) {
            $values[] = $detail;
            return '';
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());

        // then
        $this->assertEquals(['192', '168', '17'], $values);
    }

    /**
     * @test
     */
    public function shouldSliceAllUpToLimit()
    {
        // given
        $subject = '192.168.17.20';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 3, new DefaultStrategy());
        $callback = function (ReplaceDetail $detail) {
            // then
            $this->assertEquals(['192', '168', '17', '20'], $detail->all());

            return '';
        };

        // when
        $invoker->invoke($callback, new MatchStrategy());
    }

    /**
     * @test
     */
    public function shouldCreateMatchObjectWithSubject()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::standard('[0-9]+'), new Subject($subject), 2, new DefaultStrategy());
        $callback = function (ReplaceDetail $detail) use ($subject) {
            // then
            $this->assertEquals($subject, $detail->subject());

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
        $invoker = new ReplacePatternCallbackInvoker(InternalPattern::pcre('//'), new Subject(''), 0, new DefaultStrategy());

        // when
        $result = $invoker->invoke([$this, 'fail'], new MatchStrategy());

        // then
        $this->assertEquals('', $result);
    }
}
