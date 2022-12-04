<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\Agnostic\PcreDependant;
use Test\Utils\Prepared\EntityFailAssertion;
use Test\Utils\Prepared\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\PcreAutoCapture;
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
use TRegx\Pcre;

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
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture())]);
        // then
        $assertion->assertPatternRepresents('(', [new GroupOpen('')]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupNull()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture())]);

        // then
        $assertion->assertPatternRepresents('(?:)', [new GroupNull()]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupNonCapturing()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture()), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?:bar)', [new GroupOpenFlags('', new IdentityOptionSetting('')), 'bar', new GroupClose()]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture())]);
        // then
        if ($this->isPcre2()) {
            $assertion->assertPatternRepresents('(?ismx-nUJ:', [new GroupOpenFlags('ismx-nUJ', new IdentityOptionSetting('ismx-nUJ'))], '(?ismx-nUJ:');
        } else {
            $assertion->assertPatternRepresents('(?ismx--XUJ--:', [new GroupOpenFlags('ismx--XUJ--', new IdentityOptionSetting('ismx--XUJ--'))]);
        }
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlagsWithoutPatternFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture())]);
        // then
        if ($this->isPcre2()) {
            $assertion->assertPatternFlagsRepresent('(?mx-nUJ:', 's', [new GroupOpenFlags('mx-nUJ', new IdentityOptionSetting('mx-nUJ'))], '(?mx-nUJ:');
        } else {
            $assertion->assertPatternFlagsRepresent('(?mx--XUJ:', 's', [new GroupOpenFlags('mx--XUJ', new IdentityOptionSetting('mx--XUJ'))]);
        }
    }

    /**
     * @test
     */
    public function shouldNotConsumeGroupFlagsForMismatchedPcre()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new GroupConsumer(PcreAutoCapture::autoCapture())]);

        // then
        if ($this->isPcre2()) {
            $assertion->assertPatternFails('(?X:');
            $assertion->assertPatternFails('(?i--:');
        } else {
            $assertion->assertPatternFails('(?^');
        }
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlagsRemainder()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture())]);

        // then
        $assertion->assertPatternRepresents('(?m)', [new GroupRemainder('m', new IdentityOptionSetting('m'))]);
    }

    /**
     * @test
     */
    public function shouldConsumeGroupFlagsString()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture()), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?i:bar)', [new GroupOpenFlags('i', new IdentityOptionSetting('i')), 'bar', new GroupClose()]);
    }

    /**
     * @test
     */
    public function shouldNotConsumeGroupFlagsInvalid()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture()), new GroupCloseConsumer(), new LiteralConsumer()]);
        // then
        if (Pcre::pcre2()) {
            $assertion->assertPatternRepresents('(?c:bar)', [new GroupOpen(''), '?c:bar', new GroupClose()]);
        } else {
            $assertion->assertPatternRepresents('(?c:bar)', [new GroupOpen('?'), 'c:bar', new GroupClose()]);
        }
    }

    /**
     * @test
     */
    public function shouldParseGroupComment()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture()), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?#c:\)', [new GroupComment('c:\\'), new GroupClose()]);
    }

    /**
     * @test
     */
    public function shouldParseEscapedGroupClose()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupConsumer(PcreAutoCapture::autoCapture()), new GroupCloseConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('(?#c:\)hello)', [new GroupComment('c:\\'), new GroupClose(), 'hello', new GroupClose()]);
    }

    /**
     * @test
     * @dataProvider escaped
     */
    public function shouldNotConsumeEscaped(string $pattern)
    {
        // given
        $assertion = new EntityFailAssertion($this, [new GroupConsumer(PcreAutoCapture::autoCapture())]);

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
