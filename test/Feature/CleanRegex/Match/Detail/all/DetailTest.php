<?php
namespace Test\Feature\CleanRegex\Match\Detail\all;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $detail = Pattern::of('\w[\w ]+(?=,|$)')->match('Joffrey, Cersei, Ilyn Payne')->first();
        // when
        $all = $detail->all();
        // then
        $this->assertSame(['Joffrey', 'Cersei', 'Ilyn Payne'], $all);
    }
}
