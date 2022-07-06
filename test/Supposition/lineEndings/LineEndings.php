<?php
namespace Test\Supposition\TRegx\lineEndings;

use Test\Supposition\lineEndings\Ending;
use Test\Supposition\lineEndings\EndingsMap;
use Test\Utils\Functions;
use TRegx\Pcre;

trait LineEndings
{
    public function closingEndings(): array
    {
        return $this->endings(Functions::identity());
    }

    public function ignoredEndings(): array
    {
        $endings = new EndingsMap();
        return $this->endings(function (array $names) use ($endings): array {
            return \array_diff($endings->names(), $names);
        });
    }

    private function endings(callable $appliedEndingNames): array
    {
        $dataProvider = [];
        foreach ($this->commentLineEndings() as [$convention, $endingNames]) {
            foreach ($appliedEndingNames($endingNames) as $endingName) {
                $ending = new Ending($endingName);
                $dataProvider["$convention $ending"] = [$convention, $ending];
            }
        }
        return $dataProvider;
    }

    private function commentLineEndings(): array
    {
        "(*CR)        carriage return
         (*LF)        linefeed
         (*CRLF)      carriage return, followed by linefeed
         (*ANYCRLF)   any of the three above
         (*ANY)       all Unicode newline sequences
         (*NUL)       the NUL character (binary zero)";

        $conventions = [
            ''           => ['', ['lf', 'crlf', 'lfcr']],
            'lf'         => ['(*LF)', ['lf', 'crlf', 'lfcr']],
            'cr'         => ['(*CR)', ['cr', 'crlf', 'lfcr']],
            'crlf'       => ['(*CRLF)', ['crlf']],
            'anycrlf'    => ['(*ANYCRLF)', ['crlf', 'cr', 'lf', 'lfcr']],
            'any'        => ['(*ANY)', ['crlf', 'cr', 'lf', 'lfcr', 'vt', 'ff', 'nl']],

            ['(*LF)(*CR)', ['cr', 'crlf', 'lfcr']],
            ['(*CR)(*LF)', ['lf', 'crlf', 'lfcr']],
            ['(*CR)(*LF)(*CR)(*LF)(*CR)', ['cr', 'crlf', 'lfcr']],

            ['(*CRLF)(*LF)', ['lf', 'crlf', 'lfcr']],

            // PCRE Verb that doesn't change the newlines, should respond to
            // the default newlines, same as (*LF) or same as no verb.
            'irrelevant' => ['(*MARK:name)', ['lf', 'crlf', 'lfcr']],
        ];
        if (Pcre::pcre2()) {
            return $conventions + ['nul' => ['(*NUL)', []]];
        }
        return $conventions;
    }
}
