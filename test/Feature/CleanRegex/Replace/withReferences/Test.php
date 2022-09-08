<?php
namespace Test\Feature\CleanRegex\Replace\withReferences;

use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function withReferences()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->withReferences('X');
        // then
        $this->assertSame('X.X.X.X', $replaced);
    }

    /**
     * @test
     */
    public function withReferences_UsingPcreReferencesDollar()
    {
        // when
        $replaced = pattern('<(\d+)>')->replace('<127> <167>')->withReferences('($1)');
        // then
        $this->assertSame('(127) (167)', $replaced);
    }

    /**
     * @test
     */
    public function withReferences_UsingPcreReferencesBackslash()
    {
        // when
        $replaced = pattern('<(\d+)>')->replace('<127> <167>')->withReferences('(\1)');
        // then
        $this->assertSame('(127) (167)', $replaced);
    }
}
