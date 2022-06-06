<?php
namespace Test\Feature\CleanRegex\_extended\_whitespace;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldInject()
    {
        // when
        $pattern = Pattern::inject("@:bar", ["user\ninput"]);

        // then
        $this->assertConsumesFirst("user\ninput:bar", $pattern);
    }

    /**
     * @test
     */
    public function shouldInject_ExtendedMode()
    {
        // when
        $pattern = Pattern::inject("@:bar", ["user\n\vinput"], 'x');

        // then
        $this->assertConsumesFirst("user\n\vinput:bar", $pattern);
    }

    /**
     * @test
     */
    public function shouldTemplateLiteral_ExtendedMode()
    {
        // when
        $pattern = Pattern::template('@:bar', 'x')->literal("user\n\vinput");

        // then
        $this->assertConsumesFirst("user\n\vinput:bar", $pattern);
    }
}
