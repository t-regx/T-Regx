<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\callback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Group\Group;

/**
 * @coversNothing
 */
class ReplacePatternTest extends TestCase
{
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
                return $group->text() . ':' . $group->textLength();
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
}
