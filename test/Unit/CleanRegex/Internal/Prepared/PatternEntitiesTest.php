<?php
namespace Test\Unit\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\NoAutoCapture\ThrowAutoCapture;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer\ThrowPlaceholderConsumer;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\LegacyOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\PcreAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Character;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Pattern\SubpatternFlagsStringPattern;
use TRegx\CleanRegex\Internal\Prepared\PatternEntities;
use TRegx\Pcre;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\PatternEntities
 */
class PatternEntitiesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCloseGroup()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('(foo)', SubpatternFlags::empty()), PcreAutoCapture::autoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new GroupOpen(''),
            new Literal('foo'),
            new GroupClose(),
        ];
        $this->assertEquals($expected, $entities);
    }

    /**
     * @test
     */
    public function shouldParseNullByte()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('\0', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new Escaped('0')], $entities);
    }

    /**
     * @test
     */
    public function shouldParseLookAroundAssertion()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('\K', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new Escaped('K')], $entities);
    }

    /**
     * @test
     */
    public function shouldConsumeImmediatelyClosedGroupsRemainder()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('()(?)', SubpatternFlags::empty()), PcreAutoCapture::autoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $setting = Pcre::pcre2() ? new IdentityOptionSetting('') : new LegacyOptionSetting('');
        $this->assertEquals([new GroupOpen(''), new GroupClose(), new GroupRemainder('', $setting)], $entities);
    }

    /**
     * @test
     */
    public function shouldConsumeImmediatelyClosedGroupsRepeatedly()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('())))(?)', SubpatternFlags::empty()), PcreAutoCapture::autoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new GroupOpen(''),
            new GroupClose(),
            new GroupClose(),
            new GroupClose(),
            new GroupClose(),
            new GroupRemainder('', Pcre::pcre2() ? new IdentityOptionSetting('') : new LegacyOptionSetting('')),
        ];
        $this->assertEquals($expected, $entities);
    }

    /**
     * @test
     */
    public function shouldParseImmediatelyClosedCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[]]]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new ClassOpen(),
            new Character(']'),
            new ClassClose(),
            new Literal(']'),
        ];
        $this->assertEquals($expected, $entities);
    }

    /**
     * @test
     */
    public function shouldParseDoubleColorWordInCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[:alpha:]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new ClassOpen(), new Character(':alpha:'), new ClassClose()], $entities);
    }

    /**
     * @test
     */
    public function shouldParseEscapedClosingCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[F\]O]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $this->assertEquals([new ClassOpen(), new Character('F\]O'), new ClassClose()], $entities);
    }

    /**
     * @test
     */
    public function shouldParseNestedCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[01[:alpha:]%]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new ClassOpen(),
            new Character('01'),
            new Character('[:alpha:]'),
            new Character('%'),
            new ClassClose()
        ];
        $this->assertEquals($expected, $entities);
    }
}
