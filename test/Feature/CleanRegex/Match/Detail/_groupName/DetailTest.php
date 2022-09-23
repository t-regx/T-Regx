<?php
namespace Test\Feature\CleanRegex\Match\Detail\_groupName;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\DataProvider\CrossDataProviders;
use TRegx\Pcre;

class DetailTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::asciiGroupsNames()
     * @param string|int $name
     */
    public function shouldGetGroup_validName_ascii(string $name)
    {
        // given
        $pattern = Pattern("(?<$name>Bar){0}");
        $detail = $pattern->match('Foo')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group '$name', but the group was not matched");
        // when
        $detail->group($name)->text();
    }

    /**
     * @test
     * @dataProvider unicodeNames
     * @param string|int $name
     */
    public function shouldGetGroup_validName_unicode(string $name, bool $unicode)
    {
        // given
        if (Pcre::pcre2()) {
            if ($unicode) {
                $pattern = Pattern("(?<$name>Bar){0}", 'u');
            } else {
                $pattern = Pattern("(?<name>Bar){0}");
            }
        } else {
            $pattern = Pattern("(?<name>Bar){0}");
        }
        $detail = $pattern->match('Foo')->first();
        // then
        if (Pcre::pcre2()) {
            if ($unicode) {
                $this->expectException(GroupNotMatchedException::class);
                $this->expectExceptionMessage("Expected to call text() for group '$name', but the group was not matched");
            } else {
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '$name' given");
            }
        } else {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '$name' given");
        }
        // when
        $detail->group($name)->text();
    }

    public function unicodeNames(): array
    {
        return CrossDataProviders::cross(
            \provided([
                'ó', 'gróup', 'wordß', 'ßark', 'Ĝ', 'Ħ', 'ʷ', 'ƻ', 'ǅ',
                'foo_ßark', 'foo_Ĝ', 'foo_Ħ', 'foo_ʷ', 'foo_ƻ', 'foo_ǅ', 'foo_٤'
            ]),
            [
                [true],
                [false]
            ]
        );
    }

}
