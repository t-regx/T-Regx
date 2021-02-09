<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFluent()
    {
        // when
        $result = pattern("(?<capital>[A-Z])?[a-zA-Z']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Detail $detail) {
                return $detail->textLength() !== 3;
            })
            ->map(function (Detail $detail) {
                return $detail->group('capital');
            })
            ->map(function (DetailGroup $detailGroup) {
                if ($detailGroup->matched()) {
                    return "yes: $detailGroup";
                }
                return "no";
            })
            ->values()
            ->all();

        // then
        $this->assertSame(['no', 'yes: H', 'no', 'yes: A'], $result);
    }

    /**
     * @test
     */
    public function shouldFluent_filterNth()
    {
        // when
        $result = pattern("(Lorem|ipsum|dolor|emet)")
            ->match("Lorem ipsum dolor emet")
            ->fluent()
            ->filter(function (Detail $detail) {
                return !in_array($detail->text(), ['Lorem', 'ipsum']);
            })
            ->map(function (Detail $detail) {
                return $detail->text();
            })
            ->nth(1);

        // then
        $this->assertSame('emet', $result);
    }

    /**
     * @test
     */
    public function shouldFluent_passUserData()
    {
        // given
        pattern("(Foo|Bar)")
            ->match("Foo, Bar")
            ->fluent()
            ->filter(function (Detail $detail) {
                // when
                $detail->setUserData($detail === 'Foo' ? 'hey' : 'hello');

                return true;
            })
            ->forEach(function (Detail $detail) {
                // then
                $userData = $detail->getUserData();

                $this->assertSame($detail === 'Foo' ? 'hey' : 'hello', $userData);
            });
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst()
    {
        // when
        pattern("(?<capital>[A-Z])?[a-z']+")
            ->match("I'm rather old, He likes Apples")
            ->fluent()
            ->filter(function (Detail $detail) {
                return $detail->textLength() !== 3;
            })
            ->findFirst(Functions::pass())
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
        pattern("Foo")->match("Bar")->fluent()->findFirst(Functions::fail())->orThrow();
    }

    /**
     * @test
     */
    public function shouldFluent_keys_findFirst_orThrow()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        pattern("Foo")->match("Bar")->fluent()->keys()->findFirst(Functions::fail())->orThrow();
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
                ->orThrow(CustomException::class);
        } catch (CustomException $exception) {
            // then
            $this->assertSame("Expected to get the first element from fluent pattern, but the elements feed is empty.", $exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldFluent_first_throw_InternalRegxException()
    {
        try {
            // when
            pattern("Foo")->match("Foo")->fluent()->first(Functions::throws(new NoFirstStreamException()));
        } catch (NoFirstStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldFluent_findFirst_throw_InternalRegxException()
    {
        try {
            // when
            pattern("Foo")->match("Foo")->fluent()->findFirst(Functions::throws(new NoFirstStreamException()))->orThrow();
        } catch (NoFirstStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }
}
