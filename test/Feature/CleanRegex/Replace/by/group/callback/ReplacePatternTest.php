<?php
namespace Test\Feature\CleanRegex\Replace\by\group\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Pattern;

class ReplacePatternTest extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldReplace()
    {
        // given
        $subject = 'Replace One!, Two! and One!';

        // when
        $result = pattern('(?<capital>[OT])(ne|wo)')
            ->replace($subject)
            ->all()
            ->by()
            ->group('capital')
            ->callback(function (Group $group) {
                return $group->text() . ':' . $group->length();
            });

        // then
        $this->assertSame('Replace O:1!, T:1! and O:1!', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_byVariableCallback()
    {
        // when
        $result = pattern('(?<group>My_word)')
            ->replace('Word is: My_word')
            ->first()
            ->by()
            ->group('group')
            ->callback('base64_encode');

        // then
        $this->assertSame('Word is: TXlfd29yZA==', $result);
    }

    /**
     * @test
     */
    public function shouldCall_withNonMatchedGroup()
    {
        // given
        $subject = 'Replace One!, Two! and One!';

        // when
        $result = pattern('(?<capital>[OT])(ne|wo)(?<missing>Foo)?')
            ->replace($subject)
            ->all()
            ->by()
            ->group('missing')
            ->callback(function (Group $group) {
                $this->assertFalse($group->matched());
                return 'replaced';
            });

        // then
        $this->assertSame('Replace replaced!, replaced! and replaced!', $result);
    }

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroupMatchedSubject()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        Pattern::of('Foo')
            ->replace('Foo')
            ->by()
            ->group('missing')
            ->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroupUnmatchedSubject()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        Pattern::of('Foo')
            ->replace('Bar')
            ->by()
            ->group('missing')
            ->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldNotCallEverythingForUnmatchedGroupFirstGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        Pattern::of('([a\d]+[a\d]+)+3')
            ->replace('aaaaaaaaaaaaaaaaaaaa 3')
            ->first()
            ->by()
            ->group('missing')
            ->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldNotCallEverythingForUnmatchedGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        Pattern::of('([a\d]+[a\d]+)+3')
            ->replace('aa3 aaaaaaaaaaaaaaaaaaaa 3')
            ->first()
            ->by()
            ->group('missing')
            ->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldNotCallEverythingForUnmatchedGroupLimit0()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        Pattern::of('Foo')
            ->replace('Bar')
            ->only(0)
            ->by()
            ->group('missing')
            ->callback(Functions::fail());
    }
}
