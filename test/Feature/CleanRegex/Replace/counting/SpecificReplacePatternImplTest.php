<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\counting;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

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
}
