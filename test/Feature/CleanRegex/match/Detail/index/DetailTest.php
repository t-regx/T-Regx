<?php
namespace Test\Feature\CleanRegex\match\Detail\index;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGetIndex_first()
    {
        // given
        $detail = Pattern::of('\w+')->match('Socrates, Plato, Aristotle')->first();
        // when
        $index = $detail->index();
        // then
        $this->assertSame(0, $index);
    }

    /**
     * @test
     */
    public function shouldGetIndex_findFirst()
    {
        // given
        $detail = Pattern::of('\w+')->match('Socrates, Plato, Aristotle')->findFirst()->get();
        // when
        $index = $detail->index();
        // then
        $this->assertSame(0, $index);
    }

    /**
     * @test
     */
    public function shouldGetIndex_all()
    {
        // given
        $match = Pattern::of('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $details = $match->all();
        // then
        $this->assertDetailsIndexed(...$details);
    }

    /**
     * @test
     */
    public function shouldGetIndex_map()
    {
        // given
        $match = Pattern::of('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $match->map(Functions::collect($details));
        // then
        $this->assertDetailsIndexed(...$details);
    }

    /**
     * @test
     */
    public function shouldGetIndex_flatMap()
    {
        // given
        $match = Pattern::of('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $match->flatMap(Functions::collect($details, []));
        // then
        $this->assertDetailsIndexed(...$details);
    }

    /**
     * @test
     */
    public function shouldGetIndex_forEach()
    {
        // given
        $match = Pattern::of('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $match->forEach(Functions::collect($details));
        // then
        $this->assertDetailsIndexed(...$details);
    }
}
