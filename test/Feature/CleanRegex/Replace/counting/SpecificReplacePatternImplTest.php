<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\counting;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

/**
 * @coversNothing
 */
class SpecificReplacePatternImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInvoke_counting_first()
    {
        // when
        pattern('(er|ab|ay|ey)')
            ->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->counting(function (int $count) {
                $this->assertSame(1, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(4, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(4, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(4, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(4, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(4, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(1, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(0, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(1, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(2, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(2, $count);
            })
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
            ->counting(function (int $count) {
                $this->assertSame(2, $count);
            })
            ->by()
            ->group(1)
            ->orElseThrow();
    }
}
