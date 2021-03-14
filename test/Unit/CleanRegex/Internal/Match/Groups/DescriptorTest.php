<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Groups\Descriptor;

class DescriptorTest extends TestCase
{
    /**
     * @test
     */
    public function should_getGroup()
    {
        // given
        $descriptor = new Descriptor(Internal::pattern('Pattern'));

        // when
        $groups = $descriptor->getGroups();

        // then
        $this->assertSame([0], $groups);
    }

    /**
     * @test
     */
    public function should_getGroups()
    {
        // given
        $descriptor = new Descriptor(Internal::pattern('(First) (?<named>Second) (Third) (?<Fourth>. (?<nested>))'));

        // when
        $groups = $descriptor->getGroups();

        // then
        $this->assertSame([0, 1, 'named', 2, 3, 'Fourth', 4, 'nested', 5], $groups);
    }
}
