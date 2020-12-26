<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by\group\callback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

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
            ->callback(function (DetailGroup $group) {
                return $group->text() . ':' . $group->textLength();
            });

        // then
        $this->assertSame('Replace O:1!, T:1! and O:1!', $result);
    }

    /**
     * @test
     * @happyPath
     */
    public function shouldReplace_byVariableCallback()
    {
        // given
        $subject = 'My_word';

        // when
        $result = pattern('(?<group>\w+)')
            ->replace($subject)
            ->first()
            ->by()
            ->group('group')
            ->callback('base64_encode');

        // then
        $this->assertSame('TXlfd29yZA==', $result);
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
            ->callback(function (DetailGroup $group) {
                $this->assertFalse($group->matched());
                return 'replaced';
            });

        // then
        $this->assertSame('Replace replaced!, replaced! and replaced!', $result);
    }
}
