<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset_first()
    {
        // when
        pattern('\w{4,}')
            ->replace('Cześć, Tomek')
            ->first()
            ->callback(function (Match $match) {
                // when
                $offset = $match->offset();
                $byteOffset = $match->byteOffset();

                // then
                $this->assertEquals(7, $offset);
                $this->assertEquals(9, $byteOffset);

                // clean
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetOffset_forEach()
    {
        // when
        pattern('\w{4,}')
            ->replace('Cześć, Tomek i Kamil')
            ->all()
            ->callback(function (Match $match) {
                if ($match->index() !== 1) return '';

                // when
                $offset = $match->offset();
                $byteOffset = $match->byteOffset();

                // then
                $this->assertEquals(15, $offset);
                $this->assertEquals(17, $byteOffset);

                // clean
                return '';
            });
    }
}
