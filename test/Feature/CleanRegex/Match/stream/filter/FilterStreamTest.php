<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class FilterStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_filter_nth()
    {
        // when
        $result = pattern('\w+')->match('Lorem ipsum dolor')->stream()->filter(DetailFunctions::notEquals('Lorem'))->nth(1);

        // then
        $this->assertSame('dolor', $result->text());
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->match('Bar')->stream()
            ->filter(Functions::fail())
            ->findFirst(Functions::fail())
            ->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldReturn_first_FirstMatch()
    {
        // when
        $result = pattern('\w+')->match('Foo, Bar')->stream()->filter(DetailFunctions::equals('Foo'))->first();

        // then
        $this->assertSame('Foo', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_first_NotFirstMatch()
    {
        // when
        $result = pattern('\w+')->match('Foo, Bar, Dor')->stream()->filter(DetailFunctions::equals('Dor'))->first();

        // then
        $this->assertSame('Dor', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_FirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar')->stream()->filter(DetailFunctions::equals('Foo'))->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_NotFirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor')->stream()->filter(DetailFunctions::equals('Dor'))->keys()->first();

        // then
        $this->assertSame(2, $key);
    }

    /**
     * @test
     */
    public function shouldReturnFirstKeysKeyFiltered()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor')->stream()->filter(DetailFunctions::equals('Dor'))->keys()->keys()->first();
        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldCallFilterFirstKeysKey()
    {
        // when
        pattern('\w+')->match('Foo, Bar, Dor')
            ->stream()
            ->filter(Functions::pass(true))
            ->keys()
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldNotCall_TwiceForTheSameDetail()
    {
        // when
        pattern('\w+')->match('Foo, Bar, Dor, Ver, Sir')
            ->stream()
            ->filter(DetailFunctions::collecting($calls, DetailFunctions::equals('Sir')))
            ->first();

        // then
        $this->assertSame(['Foo', 'Bar', 'Dor', 'Ver', 'Sir'], $calls);
    }

    /**
     * @test
     */
    public function shouldFilter_first_untilFound()
    {
        // given
        $invokedFor = [];

        // when
        $first = pattern('(one|two|three|four|five|six)')
            ->match('one two three four five six')
            ->stream()
            ->filter(function (string $text) use (&$invokedFor) {
                $invokedFor[] = $text;
                return $text === 'four';
            })
            ->map(DetailFunctions::text())
            ->first();

        // then
        $this->assertSame('four', $first);
        $this->assertSame(['one', 'two', 'three', 'four'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // given
        $pattern = pattern('\w+')->match('One, two, three')->stream()->filter(DetailFunctions::notEquals('two'));
        $this->assertIsNotArray($pattern);

        // when
        $size = count($pattern);

        // then
        $this->assertSame(2, $size);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_NotFirstMatch_Fifth()
    {
        // when
        $key = pattern('Foo')->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['Foo', 'Bar', 15 => 'Dor']))
            ->filter(Functions::equals('Dor'))
            ->keys()
            ->first();
        // then
        $this->assertSame(15, $key);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_Assoc()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor')
            ->stream()
            ->map(DetailFunctions::text())
            ->flatMapAssoc(Functions::toMap())
            ->filter(Functions::equals('Dor'))
            ->keys()
            ->first();

        // then
        $this->assertSame('Dor', $key);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_Entry()
    {
        // when
        $entries = pattern('\w+')->match('Foo, Bar, Dor')
            ->stream()
            ->map(DetailFunctions::text())
            ->flatMapAssoc(Functions::toMap())
            ->filter(Functions::equals('Dor'))
            ->all();

        // then
        $this->assertSame(['Dor' => 'Dor'], $entries);
    }

    /**
     * @test
     */
    public function shouldFilterThousandFirst()
    {
        // when
        $first = pattern('Foo')->match('Foo')
            ->stream()
            ->flatMap(Functions::arrayOfSize(1000, ['Bar', 'Cat']))
            ->filter(Functions::equals('Bar'))
            ->first();
        // then
        $this->assertSame('Bar', $first);
    }

    /**
     * @test
     */
    public function shouldFilterThousandFirstKey()
    {
        // when
        $first = pattern('Foo')->match('Foo')
            ->stream()
            ->map(DetailFunctions::text())
            ->flatMap(Functions::arrayOfSize(1000, ['Bar', 'Cat']))
            ->flatMapAssoc(Functions::toMap())
            ->filter(Functions::equals('Bar'))
            ->keys()
            ->first();
        // then
        $this->assertSame('Bar', $first);
    }
}
