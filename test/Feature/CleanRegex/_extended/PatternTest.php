<?php
namespace Test\Feature\TRegx\CleanRegex\_extended;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
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
    public function shouldInjecttemplate_ExtendedMode()
    {
        // when
        $pattern = Pattern::template('@:bar', 'x')->literal("user\n\vinput")->build();

        // then
        $this->assertConsumesFirst("user\n\vinput:bar", $pattern);
    }
}
