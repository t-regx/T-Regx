<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence
 */
class EntitySequenceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyFlags()
    {
        // when
        $blocks = new EntitySequence(new Flags(''));

        // then
        $this->assertHasFlags('', $blocks);
    }

    /**
     * @test
     */
    public function shouldHaveFlags()
    {
        // when
        $blocks = new EntitySequence(new Flags('i'));

        // then
        $this->assertHasFlags('i', $blocks);
    }

    /**
     * @test
     */
    public function shouldAddFlag()
    {
        // given
        $blocks = new EntitySequence(new Flags(''));

        // when
        $blocks->append(new GroupOpenFlags('x'));

        // then
        $this->assertHasFlags('x', $blocks);
    }

    /**
     * @test
     */
    public function shouldRemoveFlag()
    {
        // given
        $blocks = new EntitySequence(new Flags('uim'));

        // when
        $blocks->append(new GroupOpenFlags('-i'));

        // then
        $this->assertHasFlags('um', $blocks);
    }

    /**
     * @test
     */
    public function shouldChainFlags()
    {
        // given
        $blocks = new EntitySequence(new Flags('ux'));

        // when
        $blocks->append(new GroupOpenFlags('i'));
        $blocks->append(new GroupOpenFlags('-x'));
        $blocks->append(new GroupOpenFlags('m'));

        // then
        $this->assertHasFlags('uim', $blocks);
    }

    /**
     * @test
     */
    public function shouldChainFlagsEnd()
    {
        // given
        $blocks = new EntitySequence(new Flags('ux'));

        // when
        $blocks->append(new GroupOpenFlags('i'));
        $blocks->append(new GroupOpenFlags('-x'));
        $blocks->append(new GroupOpenFlags('m'));
        $blocks->append(new GroupClose());

        // then
        $this->assertHasFlags('ui', $blocks);
    }

    /**
     * @test
     */
    public function shouldChainFlagsDoubleEnd()
    {
        // given
        $blocks = new EntitySequence(new Flags('ux'));

        // when
        $blocks->append(new GroupOpenFlags('i'));
        $blocks->append(new GroupOpenFlags('-x'));
        $blocks->append(new GroupOpenFlags('m'));
        $blocks->append(new GroupClose());
        $blocks->append(new GroupClose());

        // then
        $this->assertHasFlags('uxi', $blocks);
    }

    /**
     * @test
     */
    public function shouldChainFlagsTripleEnd()
    {
        // given
        $blocks = new EntitySequence(new Flags('ux'));

        // when
        $blocks->append(new GroupOpenFlags('i'));
        $blocks->append(new GroupOpenFlags('-x'));
        $blocks->append(new GroupOpenFlags('m'));
        $blocks->append(new GroupClose());
        $blocks->append(new GroupClose());
        $blocks->append(new GroupClose());

        // then
        $this->assertHasFlags('ux', $blocks);
    }

    /**
     * @test
     */
    public function shouldAcceptSuperfluousEnd()
    {
        // given
        $blocks = new EntitySequence(new Flags('uxi'));

        // when
        $blocks->append(new GroupOpenFlags('i'));
        $blocks->append(new GroupClose());
        $blocks->append(new GroupClose());

        // then
        $this->assertHasFlags('uxi', $blocks);
    }

    /**
     * @test
     */
    public function shouldPreferDestruction()
    {
        // given
        $blocks = new EntitySequence(new Flags('i'));

        // when
        $blocks->append(new GroupOpenFlags('x-x'));

        // then
        $this->assertHasFlags('i', $blocks);
    }

    private function assertHasFlags(string $flags, EntitySequence $blocks): void
    {
        $this->assertSame($flags, (string)$blocks->flags());
    }
}
