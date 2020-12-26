<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Match\Details\Detail;

class DictionaryMapperTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace()
    {
        // given
        $mapReplace = new DictionaryMapper(['old' => 'new']);

        // when
        $result = $mapReplace->map('old', $this->detail());

        // then
        $this->assertSame('new', $result);
    }

    /**
     * @test
     */
    public function shouldReturnNull_forMissing()
    {
        // given
        $mapReplace = new DictionaryMapper(['old' => 'new']);

        // when
        $result = $mapReplace->map('missing', $this->detail());

        // then
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidKey()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid replacement map key. Expected string, but integer (1) given');

        // when
        new DictionaryMapper([1 => 'new']);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidValue()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid replacement map value. Expected string, but integer (1) given');

        // when
        new DictionaryMapper(['old' => 1]);
    }

    private function detail(): Detail
    {
        return $this->createMock(Detail::class);
    }
}
