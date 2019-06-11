<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\Callback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;

class ReplacePatternCallbackInvokerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInvokeCallback()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(new Pattern('[0-9]+'), new SubjectableImpl($subject), 2, new DefaultStrategy());
        $callback = function (ReplaceMatch $match) {
            $value = (int)$match->text();
            return '*' . ($value + 1) . '*';
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
        $invoker = new ReplacePatternCallbackInvoker(new Pattern('[0-9]+'), new SubjectableImpl($subject), 2, new DefaultStrategy());
        $offsets = [];
        $callback = function (ReplaceMatch $match) use (&$offsets) {
            $offsets[] = $match->offset();
            return (string)$match;
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
        $invoker = new ReplacePatternCallbackInvoker(new Pattern('[0-9]+'), new SubjectableImpl($subject), 3, new DefaultStrategy());
        $values = [];
        $callback = function (ReplaceMatch $match) use (&$values) {
            $values[] = $match;
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
        $invoker = new ReplacePatternCallbackInvoker(new Pattern('[0-9]+'), new SubjectableImpl($subject), 3, new DefaultStrategy());
        $callback = function (ReplaceMatch $match) {
            // then
            $this->assertEquals(['192', '168', '17', '20'], $match->all());

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
        $invoker = new ReplacePatternCallbackInvoker(new Pattern('[0-9]+'), new SubjectableImpl($subject), 2, new DefaultStrategy());
        $callback = function (ReplaceMatch $match) use ($subject) {
            // then
            $this->assertEquals($subject, $match->subject());

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
        $invoker = new ReplacePatternCallbackInvoker(new Pattern(''), new SubjectableImpl(''), 0, new DefaultStrategy());

        // when
        $result = $invoker->invoke(function () {
            $this->assertTrue(false);
        }, new MatchStrategy());

        // then
        $this->assertEquals('', $result);
    }
}
