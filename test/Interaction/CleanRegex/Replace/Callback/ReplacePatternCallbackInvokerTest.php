<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\Callback;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\ThrowGroupAware;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\GroupKey\WholeMatch;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

/**
 * @covers \TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker
 */
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
        $invoker = new ReplacePatternCallbackInvoker(Definitions::pattern('[0-9]+'), new Subject($subject), 2, new DefaultStrategy(), new IgnoreCounting(), new ThrowGroupAware(), new WholeMatch());

        // when
        $result = $invoker->invoke(Functions::surround('*'), new MatchStrategy());

        // then
        $this->assertSame('Tom Cruise is *21* years old and has *192*cm', $result);
    }

    /**
     * @test
     */
    public function shouldPassOffsets()
    {
        // given
        $subject = 'Tom Cruise is 21 years old and has 192cm';
        $invoker = new ReplacePatternCallbackInvoker(Definitions::pattern('[0-9]+'), new Subject($subject), 2,
            new DefaultStrategy(), new IgnoreCounting(), new ThrowGroupAware(), new WholeMatch());
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
        $invoker = new ReplacePatternCallbackInvoker(Definitions::pattern('[0-9]+'), new Subject($subject), 3, new DefaultStrategy(), new IgnoreCounting(), new ThrowGroupAware(), new WholeMatch());

        // when
        $invoker->invoke(DetailFunctions::collecting($values, Functions::identity()), new MatchStrategy());

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
        $invoker = new ReplacePatternCallbackInvoker(Definitions::pattern('[0-9]+'), new Subject($subject), 3, new DefaultStrategy(), new IgnoreCounting(), new ThrowGroupAware(), new WholeMatch());
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
        $invoker = new ReplacePatternCallbackInvoker(Definitions::pattern('[0-9]+'), new Subject($subject), 2, new DefaultStrategy(), new IgnoreCounting(), new ThrowGroupAware(), new WholeMatch());
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
        $invoker = new ReplacePatternCallbackInvoker(Definitions::pcre('//'), new Subject(''), 0, new DefaultStrategy(), new IgnoreCounting(), new ThrowGroupAware(), new WholeMatch());

        // when
        $result = $invoker->invoke(Functions::fail(), new MatchStrategy());

        // then
        $this->assertSame('', $result);
    }
}
