<?php
namespace Test\Feature\CleanRegex\Replace\counting;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Match\Details\Structure;

class SpecificReplacePatternImplTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldInvoke_counting_first()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->counting(Functions::assertSame(1))
            ->with('$1');
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_all()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->all()
            ->counting(Functions::assertSame(4))
            ->with('$1');
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_all_focus()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->all()
            ->counting(Functions::assertSame(4))
            ->focus(1)
            ->with('$1');
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_all_focus_by_group()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->all()
            ->counting(Functions::assertSame(4))
            ->focus(1)
            ->by()
            ->group(1)
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_all_focus_callback()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->all()
            ->counting(Functions::assertSame(4))
            ->focus(1)
            ->by()
            ->group(1)
            ->callback(Functions::constant(''));
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_only()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->only(99)
            ->counting(Functions::assertSame(4))
            ->with('$1');
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_first_withReferences()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->counting(Functions::assertSame(1))
            ->withReferences('$1');
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_forUnmatchedSubject()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('Foo')
            ->first()
            ->counting(Functions::assertSame(0))
            ->with('$1');
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_first_callback()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->counting(Functions::assertSame(1))
            ->callback(Functions::constant(''));
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_first_by_map()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->only(2)
            ->counting(Functions::assertSame(2))
            ->by()
            ->map(['er' => 'ER', 'ab' => 'AB']);
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_first_by_group_map()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->only(2)
            ->counting(Functions::assertSame(2))
            ->by()
            ->group(1)
            ->map(['er' => 'ER', 'ab' => 'AB'])
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldInvoke_counting_first_by_group_orElseThrow()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->only(2)
            ->counting(Functions::assertSame(2))
            ->by()
            ->group(1)
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldStructureGetSubject()
    {
        // when
        pattern('Bar')
            ->replace('Lorem ipsum Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertSame('Lorem ipsum Bar', $structure->subject());
            })
            ->with('Bar');
    }

    /**
     * @test
     */
    public function shouldCallbackStructureGetGroupNames()
    {
        // when
        pattern('(?<value>Foo) or (Bar)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertSame(['value', null], $structure->groupNames());
            })
            ->callback(Functions::constant('Bar'));
    }

    /**
     * @test
     */
    public function shouldStructureGetGroupNames()
    {
        // when
        pattern('(?<value>Foo) or (Bar)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertSame(['value', null], $structure->groupNames());
            })
            ->with('Bar');
    }

    /**
     * @test
     */
    public function shouldStructureGetGroupsCount()
    {
        // when
        pattern('(?<value>Foo) or (Bar)(?:)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertSame(2, $structure->groupsCount());
            })
            ->with('Bar');
    }

    /**
     * @test
     */
    public function shouldStructureHaveGroupWith()
    {
        // when
        pattern('(?<value>Foo) or (Bar)(?:)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertTrue($structure->groupExists(0));
                $this->assertTrue($structure->groupExists(1));
                $this->assertTrue($structure->groupExists(2));
                $this->assertTrue($structure->groupExists('value'));

                $this->assertFalse($structure->groupExists(3));
                $this->assertFalse($structure->groupExists('missing'));
            })
            ->with('Bar');
    }

    /**
     * @test
     */
    public function shouldStructureHaveGroupCallback()
    {
        // when
        pattern('(?<value>Foo) or (Bar)(?:)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertTrue($structure->groupExists(0));
                $this->assertTrue($structure->groupExists(1));
                $this->assertTrue($structure->groupExists(2));
                $this->assertTrue($structure->groupExists('value'));

                $this->assertFalse($structure->groupExists(3));
                $this->assertFalse($structure->groupExists('missing'));
            })
            ->callback(Functions::constant('Bar'));
    }

    /**
     * @test
     */
    public function shouldStructureHaveGroupByMap()
    {
        // when
        pattern('(?<value>Foo) or (Bar)(?:)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertTrue($structure->groupExists(0));
                $this->assertTrue($structure->groupExists(1));
                $this->assertTrue($structure->groupExists(2));
                $this->assertTrue($structure->groupExists('value'));

                $this->assertFalse($structure->groupExists(3));
                $this->assertFalse($structure->groupExists('missing'));
            })
            ->by()
            ->map(['Foo or Bar' => 'Replaced']);
    }

    /**
     * @test
     */
    public function shouldStructureHaveGroupByGroupMap()
    {
        // when
        pattern('(?<value>Foo) or (Bar)(?:)')
            ->replace('Foo or Bar')
            ->counting(function (int $count, Structure $structure) {
                $this->assertTrue($structure->groupExists(2));
                $this->assertTrue($structure->groupExists('value'));

                $this->assertFalse($structure->groupExists(3));
                $this->assertFalse($structure->groupExists('missing'));
            })
            ->by()
            ->group('value')
            ->map(['Foo' => 'Replaced'])
            ->orElseThrow();
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedGroupName()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2malformed' given");

        // when
        pattern('Foo')
            ->replace('Foo')
            ->counting(function (int $count, Structure $structure) {
                $structure->groupExists('2malformed');
            })
            ->with('Bar');
    }
}
