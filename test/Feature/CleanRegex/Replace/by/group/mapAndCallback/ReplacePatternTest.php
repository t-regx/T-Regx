<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\mapAndCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace()
    {
        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace('Replace One!, Two! and One!')
            ->all()
            ->by()
            ->group('capital')
            ->mapAndCallback(['O' => 'a', 'T' => 'b'], 'strToUpper')
            ->orElseThrow();

        // then
        $this->assertSame('Replace A!, B! and A!', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forMissingReplacement()
    {
        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'One' by group 'capital' ('O'), but such key is not found in replacement map");

        // when
        pattern('(?<capital>O)?ne')
            ->replace('One')
            ->all()
            ->by()
            ->group('capital')
            ->mapAndCallback([], Functions::fail())
            ->orElseThrow();
    }
}
