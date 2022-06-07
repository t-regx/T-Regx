<?php
namespace Test\Feature\CleanRegex\Match\Detail\index;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;

class DetailTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGetIndex_first()
    {
        // given
        $detail = pattern('\w+')->match('Socrates, Plato, Aristotle')->first();
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
        $detail = pattern('\w+')->match('Socrates, Plato, Aristotle')->findFirst()->get();
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
        $match = pattern('\w+')->match('Socrates, Plato, Aristotle');
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
        $match = pattern('\w+')->match('Socrates, Plato, Aristotle');
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
        $match = pattern('\w+')->match('Socrates, Plato, Aristotle');
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
        $match = pattern('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $match->forEach(Functions::collect($details));
        // then
        $this->assertDetailsIndexed(...$details);
    }
}
