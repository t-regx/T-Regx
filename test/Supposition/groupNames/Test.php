<?php
namespace Test\Supposition\groupNames;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseConditional;
use TRegx\Pcre;
use TRegx\SafeRegex\preg;

class Test extends TestCase
{
    use TestCaseConditional;

    /**
     * @test
     * @dataProvider groupNames
     */
    public function shouldAcceptDifferentGroupNames_onUnicode(string $groupName): void
    {
        // given
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary("Unicode group names are only available in PCRE2");
        }
        // when
        preg::match("/(?<$groupName>Foo)/u", 'Foo', $match);
        // then
        $this->assertSame(['Foo', $groupName => 'Foo', 'Foo'], $match);
    }

    public function groupNames(): array
    {
        return \provided(['gróup', 'ßark']);
    }
}
