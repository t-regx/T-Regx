<?php
namespace Test\Unit\TRegx\CleanRegex\Analyze\Simplify;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Analyze\Simplify\PatternSimplifier;
use TRegx\CleanRegex\Exception\CleanRegex\InvalidPatternException;
use TRegx\CleanRegex\Internal\InternalPattern;

class PatternSimplifierTest extends TestCase
{
    /**
     * @test
     * @dataProvider toBeSimplified
     * @param string $complex
     * @param string $expected
     */
    public function shouldSimplify(string $complex, string $expected)
    {
        // given
        $simplifier = new PatternSimplifier(new InternalPattern($complex));

        // when
        $simplified = $simplifier->simplify();

        // then
        $this->assertEquals($expected, $simplified);
    }

    public function toBeSimplified()
    {
        return [
            // Character groups - repeating
            'repeating char group'       => ['Repeat [abca]', 'Repeat [abc]'],

            // Character groups (digits)
            'numbers token \d #1'        => ['Number [0-9]', 'Number \d'],
            'numbers token \d #2'        => ['Word [0-9]as]', 'Word \das]'],
            'numbers token \D #3'        => ['Not numbers [^0-9]', 'Not numbers \D'],

            // Character groups (word)
            'word token \w #1'           => ['Word [a-zA-Z0-9_]', 'Word \w'],
            'word token \w #2'           => ['Word [A-Za-z_0-9]', 'Word \w'],
            'word token \w #3'           => ['Word [A-Z0-9a-z_]', 'Word \w'],
            'word token \W #1'           => ['Not a word [^a-zA-Z0-9_]', 'Not a word \W'],
            'word token \W #2'           => ['Not a word [^A-Z0-9_a-z]', 'Not a word \W'],
            'word token \W #3'           => ['Not a word [^A-Z_0-9a-z]', 'Not a word \W'],

            // Unnecessary escaping
            'unnecessary escape'         => ['Apostrophe \" and space\ ', 'Apostrophe " and space '],
            'unnecessary escape \m'      => ['Word \m', 'Word m'],
            'unnecessary escape (group)' => ['Group escaping [\.\]\|\?\[\)]', 'Group escaping [.\]|?[)]'],

            // Character groups - corner cases
            'single char group #1'       => ['Word [a]', 'Word a'],
            'single char group #2'       => ['Word [\a]', 'Word \a'],
            'single char group #3'       => ['Word [\]]', 'Word \]'],
            'single char group #4'       => ['Word [)]', 'Word \)'],
            'single char group #5'       => ['Word [[]', 'Word \['],

            // Quantifiers
            'quantifiers ?'              => ['Word{0,1}', 'Word?'],
            'quantifiers *'              => ['Maybe{0,}', 'Maybe*'],
            'quantifiers +'              => ['Maybe{1,}', 'Maybe+'],

            // Quoted
            'quoted alt'                 => ['\Q(b|c|d)\E [0-9]', '\Q(b|c|d)\E \d'],

            // Alternatives
            'alt #1'                     => ['(?:b|c|d|\.)', '[bcd.]'],
            'alt #2'                     => ['(?:b|\(|\]|\.)', '[b(\].]'],
            'alt #3'                     => ['(?:b|\(\|)', '[b(|]'],
            'alt #4'                     => ['(?:b|c|d) [0-9]', '[bcd] \d'],
            'alt #5'                     => ['(?:a|b|c)', '[abc]'],
            'alt $6'                     => ['(?:a|b|c|\.|d)', '[abc.d]'],

            // Escaped control characters
            [
                '\A\B\C\D\E\F\G\H\I\J\K\M\N\O\PP\Q\E\R\S\T\V\W\X\Y\Z',
                '\A\B\C\D\EF\G\HIJ\KM\NO\PP\Q\E\R\ST\V\W\XY\Z',
            ],
            [
                "((?'name'))" . '\a\b\c2\d\e\f\g{2}\h\i\j\k\'name\'\m\n\o{1}\pP\q\r\s\t\v\w\x\y\z',
                "((?'name'))" . '\a\b\c2\d\e\f\g{2}\hij\k\'name\'m\n\o{1}\pPq\r\s\t\v\w\xy\z',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider notToBeSimplified
     * @param string $escaped
     */
    public function shouldNotSimplify_escaped(string $escaped)
    {
        // given
        $simplifier = new PatternSimplifier(new InternalPattern($escaped));

        // when
        $simplified = $simplifier->simplify();

        // then
        $this->assertEquals($escaped, $simplified);
    }

    public function notToBeSimplified()
    {
        return [
            // Escaped
            'escaped group #1'      => ['Number \[0-9]'],
            'escaped group #2'      => ['Word [0-9\]as]'],
            'escaped quantifier #1' => ['Word\{0,1}'],
            'escaped quantifier #2' => ['Maybe\{0,}'],
            'escaped quantifier #3' => ['Maybe\{1,}'],
            'escaped quantifier #4' => ['Maybe{1,\}'],
            'escaped quantifier #5' => ['Maybe{1\,}'],
            'escaped alt'           => ['One word groups \(b|c|d\)'],
            'escaped char #1 \a'    => ['Word \a'],
            'escaped char #2 \b'    => ['Word \b'],
            'escaped char #2 \\\\b' => ['Word \\\\b'],

            // Quoted
            'quote #1'              => ['Number \Q[0-9]'],
            'quote #2'              => ['Not numbers \Q[^0-9]'],
            'quote #3'              => ['Word \Q[a-zA-Z0-9_]'],
            'quote #4'              => ['Not a word \Q[^a-zA-Z0-9_]'],
            'quote #5'              => ['Letter \Q\and'],
            'quote #6'              => ['Apostrophe  \Q\and\"'],
            'quote #7'              => ['Group \Q[a] escaping [\.\]\|\?\[\)]'],
            'quote #8'              => ['Group \Q[]0-9]'],
            'quote #9'              => ['Word \Q[0-9]as\]'],
            'quote #10'             => ['Word \Q{0,1}'],
            'quote #11'             => ['Maybe \Q{0,}'],
            'quote #12'             => ['One word groups \Q(b|c|d)'],
            'quote #13'             => ['Escaped alternative \Q\(b|c|d\)'],

            // Quoted alternative
            'quoted alt'            => ['One word groups (?:b|\Q\(|\E)'],

            // Groups
            'unclosed group #1'     => ['Group []0-9]'],
            'unclosed group #2'     => ['Group [0-9[]'],

            // Alternatives
            'alternative #1'        => ['One word groups (b|c|d|\.)'],
            'alternative #2'        => ['One word groups (?<name>b|c|d|\.)'],
            'alternative #3'        => ['One word groups (?P<name>b|c|d|\.)'],
            'alternative #4'        => ['One word groups (?\'name\'b|c|d|\.)'],

            // Back reference
            'back reference #1'     => ['group () \1'],
            'back reference #2'     => ['Group (()) \2']
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalid()
    {
        // given
        $simplifier = new PatternSimplifier(new InternalPattern('invalid (a|'));

        // then
        $this->expectException(InvalidPatternException::class);

        // when
        $simplifier->simplify();
    }

    /**
     * @test
     */
    public function shouldSimplify_multipleEscaping()
    {
        // given
        $escaped = str_repeat('\\', 12);
        $simplifier = new PatternSimplifier(new InternalPattern("$escaped{1,}"));

        // when
        $simplified = $simplifier->simplify();

        $this->assertEquals($escaped . '+', $simplified);
    }

    /**
     * @test
     */
    public function shouldSimplify_not_multipleEscaping()
    {
        // given
        $escaped = str_repeat('\\', 13);
        $simplifier = new PatternSimplifier(new InternalPattern("$escaped{1,}"));

        // when
        $simplified = $simplifier->simplify();

        $this->assertEquals($escaped . '{1,}', $simplified);
    }
}
