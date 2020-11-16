<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;
use TRegx\CleanRegex\Match\Details\Detail;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFluent()
    {
        // when
        $result = pattern("(?<capital>[A-Z])?[\w']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Detail $match) {
                return $match->textLength() !== 3;
            })
            ->map(function (Detail $match) {
                return $match->group('capital');
            })
            ->map(function (DetailGroup $matchGroup) {
                if ($matchGroup->matched()) {
                    return "yes: $matchGroup";
                }
                return "no";
            })
            ->all();

        // then
        $this->assertEquals(['no', 'yes: H', 'no', 'yes: A'], $result);
    }

    /**
     * @test
     */
    public function shouldFluent_filterNth()
    {
        // when
        $result = pattern("\w+")
            ->match("Lorem ipsum dolor emet")
            ->fluent()
            ->filter(function (Detail $match) {
                return !in_array($match->text(), ['Lorem', 'ipsum']);
            })
            ->map(function (Detail $match) {
                return $match->text();
            })
            ->nth(1);

        // then
        $this->assertEquals('emet', $result);
    }

    /**
     * @test
     */
    public function shouldFluent_passUserData()
    {
        // given
        pattern("\w+")
            ->match("Foo, Bar")
            ->fluent()
            ->filter(function (Detail $match) {
                // when
                $match->setUserData($match === 'Foo' ? 'hey' : 'hello');

                return true;
            })
            ->forEach(function (Detail $match) {
                // then
                $userData = $match->getUserData();

                $this->assertEquals($match === 'Foo' ? 'hey' : 'hello', $userData);
            });
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst()
    {
        // when
        pattern("(?<capital>[A-Z])?[\w']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Detail $match) {
                return $match->textLength() !== 3;
            })
            ->findFirst(function (Detail $match) {
                $this->assertTrue(true);
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orThrow()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        pattern("Foo")
            ->match("Bar")
            ->fluent()
            ->findFirst(Functions::fail())
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_orThrow_custom()
    {
        try {
            // when
            pattern("Foo")
                ->match("Bar")
                ->fluent()
                ->findFirst(Functions::fail())
                ->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertEquals("Expected to get the first element from fluent pattern, but the elements feed is empty.", $exception->getMessage());
            $this->assertEquals("Bar", $exception->subject);
        }
    }
}
