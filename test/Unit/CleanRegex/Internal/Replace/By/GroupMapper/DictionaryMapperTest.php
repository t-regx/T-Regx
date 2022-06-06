<?php
namespace Test\Unit\CleanRegex\Internal\Replace\By\GroupMapper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\ThrowDetail;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;

/**
 * @covers \TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper
 */
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
        $result = $mapReplace->map('old', new ThrowDetail());
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
        $result = $mapReplace->map('missing', new ThrowDetail());
        // then
        $this->assertNull($result);
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
}
