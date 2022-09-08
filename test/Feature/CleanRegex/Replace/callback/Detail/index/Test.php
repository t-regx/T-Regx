<?php
namespace Test\Feature\CleanRegex\Replace\callback\Detail\index;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex_replace_first()
    {
        // given
        pattern('\d+')->replace('111-222-333')->first()->callback(DetailFunctions::out($detail, ''));
        // when
        $index = $detail->index();
        // then
        $this->assertSame(0, $index);
    }

    /**
     * @test
     * @dataProvider iteratingReplaceMethods
     * @param string $method
     * @param array $arguments
     */
    public function shouldGetIndex_replace(string $method, array $arguments)
    {
        pattern('\d+')
            ->replace('111-222-333')
            ->$method(...$arguments)
            ->callback(Functions::collect($details, ''));
        // then
        [$first, $second, $third] = $details;
        $this->assertSame(0, $first->index());
        $this->assertSame(1, $second->index());
        $this->assertSame(2, $third->index());
    }

    public function iteratingReplaceMethods(): array
    {
        return [
            ['all', []],
            ['only', [3]],
        ];
    }
}
