<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\EntityFailAssertion;
use Test\Utils\PatternEntitiesAssertion;
use Test\Utils\PcreDependant;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupComment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupNull;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\DataProvider\DataProviders;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer
 */
class GroupConsumerTest extends TestCase
{
    use PcreDependant;

    /**
     * @test
     */
    public function shouldConsumeGroup()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer()]);

        // then
        $assertion->assertPatternRepresents('(', [new GroupOpen()]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupNull()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?:)', [new GroupNull()]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupNonCapturing()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?:bar)', [new GroupOpenFlags(''), 'bar', new GroupClose()]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer()]);

        // then
        if ($this->isPcre2()) {
            $assertion->assertPatternRepresents('(?ismx-nUJ:', [new GroupOpenFlags('ismx-nUJ')]);
        } else {
            $assertion->assertPatternRepresents('(?ismx--XUJ--:', [new GroupOpenFlags('ismx--XUJ--')]);
        }
    }

    /**
     * @test
     */
    public function shouldconsumeGroupFlagsWithoutPatternFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer()]);

        // then
        if ($this->isPcre2()) {
            $assertion->assertPatternFlagsRepresent('(?mx-nUJ:', 's', [new GroupOpenFlags('mx-nUJ')]);
        } else {
            $assertion->assertPatternFlagsRepresent('(?mx--XUJ:', 's', [new GroupOpenFlags('mx--XUJ')]);
        }
    }

    /**
     * @test
     */
    public function shouldNotConsumeGroupFlagsForMismatchedPcre()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new GroupConsumer()]);

        // then
        if ($this->isPcre2()) {
            $assertion->assertPatternFails('(?X:');
            $assertion->assertPatternFails('(?i--:');
        } else {
            $assertion->assertPatternFails('(?n:');
        }
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlagsRemainder()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?m)', [new GroupRemainder('m')]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlagsString()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?i:bar)', [new GroupOpenFlags('i'), 'bar', new GroupClose()]);
    }

    /**
     * @test
     */
    public function shouldNotConsumeGroupFlagsInvalid()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?c:bar)', [new GroupOpen(), '?c:bar', new GroupClose()]);
    }

    /**
     * @test
     */
    public function shouldParseGroupComment()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?#c:\)', [new GroupComment('c:\\')]);
    }

    /**
     * @test
     */
    public function shouldParseEscapedGroupClose()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?#c:\)hello)', [new GroupComment('c:\\'), 'hello', new GroupClose()]);
    }

    /**
     * @test
     * @dataProvider escaped
     */
    public function shouldNotConsumeEscaped(string $pattern)
    {
        // given
        $assertion = new EntityFailAssertion($this, [new GroupConsumer()]);

        // then
        $assertion->assertPatternFails($pattern);
    }

    public function escaped(): array
    {
        return DataProviders::each(['\(', '\()', '\(?:', '\(?:)']);
    }

    /**
     * @test
     */
    public function shouldNotConsumeEscapedClose()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new GroupCloseConsumer()]);

        // then
        $assertion->assertPatternFails('\)');
    }
}
