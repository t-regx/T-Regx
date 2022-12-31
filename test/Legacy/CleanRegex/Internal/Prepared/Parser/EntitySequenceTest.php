<?php
namespace Test\Legacy\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Prepared\StandardSubpatternFlags;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence
 */
class EntitySequenceTest extends TestCase
{
    use StandardSubpatternFlags;

    /**
     * @test
     */
    public function shouldEmptyFlagsNotBeExtended()
    {
        $this->assertIsNotExtended(new EntitySequence($this->subpatternFlagsStandard()));
    }

    /**
     * @test
     */
    public function shouldFlagsBeExtended()
    {
        $this->assertIsExtended(new EntitySequence($this->subpatternFlagsExtended()));
    }

    /**
     * @test
     * @depends shouldEmptyFlagsNotBeExtended
     */
    public function shouldAddFlag()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsStandard());
        // when
        $sequence->append(new GroupOpenFlags('x', new IdentityOptionSetting('x')));
        // then
        $this->assertIsExtended($sequence);
    }

    /**
     * @test
     * @depends shouldFlagsBeExtended
     */
    public function shouldRemoveFlag()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('-x', new IdentityOptionSetting('-x')));
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     * @depends shouldRemoveFlag
     */
    public function shouldRemoveFlagMultiple()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('-x', new IdentityOptionSetting('-x')));
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     * @depends shouldRemoveFlag
     */
    public function shouldChainFlags()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('i', new IdentityOptionSetting('i')));
        $sequence->append(new GroupOpenFlags('-x', new IdentityOptionSetting('-x')));
        $sequence->append(new GroupOpenFlags('m', new IdentityOptionSetting('m')));
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     * @depends shouldChainFlags
     */
    public function shouldChainFlagsEnd()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('i', new IdentityOptionSetting('i')));
        $sequence->append(new GroupOpenFlags('-x', new IdentityOptionSetting('-x')));
        $sequence->append(new GroupOpenFlags('m', new IdentityOptionSetting('m')));
        $sequence->append(new GroupClose());
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     * @depends shouldChainFlagsEnd
     */
    public function shouldChainFlagsDoubleEnd()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('i', new IdentityOptionSetting('i')));
        $sequence->append(new GroupOpenFlags('-x', new IdentityOptionSetting('-x')));
        $sequence->append(new GroupOpenFlags('m', new IdentityOptionSetting('m')));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());
        // then
        $this->assertIsExtended($sequence);
    }

    /**
     * @test
     * @depends shouldChainFlagsDoubleEnd
     */
    public function shouldChainFlagsTripleEnd()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('i', new IdentityOptionSetting('i')));
        $sequence->append(new GroupOpenFlags('-x', new IdentityOptionSetting('-x')));
        $sequence->append(new GroupOpenFlags('m', new IdentityOptionSetting('m')));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());
        // then
        $this->assertIsExtended($sequence);
    }

    /**
     * @test
     * @depends shouldEmptyFlagsNotBeExtended
     */
    public function shouldAcceptSuperfluousEnd()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsExtended());
        // when
        $sequence->append(new GroupOpenFlags('i', new IdentityOptionSetting('i')));
        $sequence->append(new GroupClose());
        $sequence->append(new GroupClose());
        // then
        $this->assertIsExtended($sequence);
    }

    /**
     * @test
     * @depends shouldFlagsBeExtended
     */
    public function shouldPreferDestruction()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsStandard());
        // when
        $sequence->append(new GroupOpenFlags('x-x', new IdentityOptionSetting('x-x')));
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     * @depends shouldPreferDestruction
     */
    public function shouldPreferDestructionCaseSensitive()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsStandard());
        // when
        $sequence->append(new GroupOpenFlags('x-X', new IdentityOptionSetting('x-X')));
        // then
        $this->assertIsExtended($sequence);
    }

    /**
     * @test
     * @depends shouldFlagsBeExtended
     */
    public function shouldCountDestructionFromFirst()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsStandard());
        // when
        $sequence->append(new GroupOpenFlags('x-x-i', new IdentityOptionSetting('x-x-i')));
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     */
    public function shouldEmptyDestructRemainNotExtended()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsStandard());
        // when
        $sequence->append(new GroupOpenFlags('-', new IdentityOptionSetting('-')));
        // then
        $this->assertIsNotExtended($sequence);
    }

    /**
     * @test
     */
    public function shouldAddExtended_ManyFlags_ExtendedNotFirst()
    {
        // given
        $sequence = new EntitySequence($this->subpatternFlagsStandard());
        // when
        $sequence->append(new GroupOpenFlags('Ux', new IdentityOptionSetting('Ux')));
        // then
        $this->assertIsExtended($sequence);
    }

    private function assertIsExtended(EntitySequence $sequence): void
    {
        $this->assertTrue($sequence->flags()->isExtended());
    }

    private function assertIsNotExtended(EntitySequence $sequence): void
    {
        $this->assertFalse($sequence->flags()->isExtended());
    }
}
