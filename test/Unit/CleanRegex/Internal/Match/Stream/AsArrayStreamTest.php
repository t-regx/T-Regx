<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Stream\AsArrayStream;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\UserData;

class AsArrayStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $stream = new AsArrayStream(...$this->baseStream());

        // when
        $all = $stream->all();

        // then
        $expected = [
            ['Foo::true', null, 'type' => 'true', 'true'],
            ['Bar:13:', '13', 'type' => null, null],
            ['Lorem:997:', '997', 'type' => null, null]
        ];
        $this->assertSame($expected, $all);
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $stream = new AsArrayStream(...$this->baseStream());

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['Foo::true', null, 'type' => 'true', 'true'], $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = new AsArrayStream($this->mock('firstKey', 123), $this->baseMock());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(123, $firstKey);
    }

    private function baseStream(): array
    {
        $base = new ApiBase(
            Internal::pattern('(?:Foo|Bar|Lorem):(\d+)?:(?<type>true|false|null)?'),
            'Foo::true Bar:13: Lorem:997:',
            new UserData()
        );
        return [new BaseStream($base), $base];
    }

    private function mock(string $methodName, $value): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method($methodName)->willReturn($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function baseMock(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->never())->method($this->anything());
        return $base;
    }
}
