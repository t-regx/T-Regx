<?php
namespace Test\Supposition\groupNames;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\preg;

class Test extends TestCase
{
    /**
     * @test
     * @dataProvider groupNamesByLocales
     */
    public function shouldAcceptDifferentGroupNames_onUnicode(string $groupName): void
    {
        // when
        preg::match("/(?<$groupName>Foo)/u", 'Foo', $match);
        // then
        $this->assertSame(['Foo', $groupName => 'Foo', 'Foo'], $match);
    }

    public function groupNamesByLocales(): array
    {
        return \provided(['gróup', 'ßark']);
    }
}
