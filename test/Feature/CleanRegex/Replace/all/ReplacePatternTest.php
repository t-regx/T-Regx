<?php
namespace Test\Feature\CleanRegex\Replace\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceWithString()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->all()->with('*');

        // then
        $this->assertSame('P. Sh*man, 42 Wall*y w*, Sydn*', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithCallback()
    {
        // when
        $replaced = Pattern::of('white')
            ->replace('Gandalf the white')
            ->callback(Functions::constant('fool'));

        // then
        $this->assertSame('Gandalf the fool', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceWithMatchedGroup()
    {
        // when
        $result = Pattern::of('Foo(?<matched>Bar)')
            ->replace('FooBar')
            ->all()
            ->callback(function (Detail $detail) {
                return $detail->group('matched');
            });

        // then
        $this->assertSame('Bar', $result);
    }

    /**
     * @test
     */
    public function shouldThrowForReplacementWithUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'unmatched', but the group was not matched");
        // when
        Pattern::of('Foo(?<unmatched>Bar)?')
            ->replace('Foo')
            ->callback(function (Detail $detail) {
                return $detail->group('unmatched');
            });
    }

    /**
     * @test
     */
    public function shouldThrow_OnNonStringReplacement()
    {
        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but integer (123) given');
        // when
        Pattern::of('Foo')->replace('Foo')->callback(Functions::constant(123));
    }

    /**
     * @test
     */
    public function shouldReplace_focus_with()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com, http://other.org and http://website.org.';

        // when
        $result = pattern($pattern)->replace($subject)->all()->focus('name')->with('xxx');

        // then
        $this->assertSame('Links: http://xxx.com, http://xxx.org and http://xxx.org.', $result);
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_all()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        pattern($pattern)->replace($subject)->all()->callback(function (ReplaceDetail $detail) {
            // then
            $this->assertSame(['http://google.com', 'http://other.org', 'http://danon.com'], $detail->all());

            return '';
        });
    }

    /**
     * @test
     */
    public function shouldCaptureReplaceDetail()
    {
        // when
        Pattern::of('[a-z]+')
            ->replace('...hello there, general kenobi')
            ->callback(DetailFunctions::out($detail, 'replacement'));
        // then
        $this->assertSame(['hello', 'there', 'general', 'kenobi'], $detail->all());
        $this->assertSame('...hello there, general kenobi', $detail->subject());
        $this->assertSame('hello', $detail->text());
        $this->assertSame(0, $detail->index());
        $this->assertSame(3, $detail->offset());
        $this->assertSame(3, $detail->modifiedOffset());
    }

    /**
     * @test
     */
    public function shouldGetFromReplaceMatch_offset()
    {
        // given
        $pattern = 'http://(?<name>[a-zę]+)\.(?<domain>com|org)';
        $subject = 'Links: http://googlę.com and http://other.org. and again http://danon.com';

        $offsets = [];

        $callback = function (ReplaceDetail $detail) use (&$offsets) {
            $offsets[] = $detail->offset();
            return 'ę';
        };

        // when
        pattern($pattern)->replace($subject)->all()->callback($callback);

        // then
        $this->assertSame([7, 29, 57], $offsets);
    }

    /**
     * @test
     */
    public function shouldGetGroup_offset()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(?<domain>com|org)';
        $subject = 'Links: http://google.com and http://other.org. and again http://danon.com';

        // when
        pattern($pattern)->replace($subject)->all()->callback(function (ReplaceDetail $detail) {
            $matchGroup = $detail->group('name');
            if ($matchGroup->text() !== 'other') return '';

            // then
            $offset = $detail->offset();
            $groupOffset = $detail->group('name')->offset();

            // when
            $this->assertSame(29, $offset);
            $this->assertSame(36, $groupOffset);

            return '';
        });
    }
}
