<?php
namespace Test\Feature\CleanRegex\_figures\_backtracking;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldAllowBacktrackingMask()
    {
        // given
        $pattern = Pattern::mask('*Bar', ['*' => 'FooBar|Foo']);
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAllowBacktrackingTemplateMask()
    {
        // given
        $pattern = Pattern::template('@Bar')->mask('*', ['*' => 'FooBar|Foo']);
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
        $this->assertConsumesFirst('FooBarBar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAllowBacktrackingTemplateAlteration()
    {
        // given
        $pattern = Pattern::template('@Bar')->alteration(['FooBar', 'Foo']);
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
        $this->assertConsumesFirst('FooBarBar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAllowBacktrackingTemplatePattern()
    {
        // given
        $pattern = Pattern::template('@Bar')->pattern('FooBar|Foo');
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
        $this->assertConsumesFirst('FooBarBar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAllowBacktrackingBuilderTemplateMask()
    {
        // given
        $pattern = Pattern::builder('@Bar')->mask('*', ['*' => 'FooBar|Foo'])->build();
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
        $this->assertConsumesFirst('FooBarBar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAllowBacktrackingBuilderTemplateAlteration()
    {
        // given
        $pattern = Pattern::builder('@Bar')->alteration(['FooBar', 'Foo'])->build();
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
        $this->assertConsumesFirst('FooBarBar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAllowBacktrackingBuilderTemplatePattern()
    {
        // given
        $pattern = Pattern::builder('@Bar')->pattern('FooBar|Foo')->build();
        // when, then
        $this->assertConsumesFirst('FooBar', $pattern);
        $this->assertConsumesFirst('FooBarBar', $pattern);
    }
}
