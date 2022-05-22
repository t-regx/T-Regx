<?php
namespace Test\Utils;

use TRegx\Pcre;

trait PcreDependant
{
    public static abstract function assertSame($expected, $actual, string $message = ''): void;

    public function pcreDependentStructure(array $pcre1Patterns, array $pcre2Patterns): array
    {
        $this->assertStructuresCompatible(array_values($pcre1Patterns), array_values($pcre2Patterns));
        return $this->pcreDependant($pcre1Patterns, $pcre2Patterns);
    }

    private function pcreDependant(array $pcre1Patterns, array $pcre2Patterns): array
    {
        /**
         * I intentionally don't use {@see Pcre::pcre2}, because if I had used it,
         * and there is a bug in {@see Pcre} or any PCRE-related code, then these
         * tests would become unable to find that bug. By using {@see Pcre} in code
         * and {@see PHP_VERSION_ID} in the test, I make sure these remain unrelated
         * and the tests are capable of finding bugs.
         */
        if ($this->isPcre2()) {
            return $pcre2Patterns;
        }
        return $pcre1Patterns;
    }

    private function assertStructuresCompatible(array $array1, array $array2): void
    {
        foreach (Iterables::zip($array1, $array2) as [[$item1], [$item2]]) {
            $this->assertSame($item1, $item2);
        }
    }

    public function isPcre2(): bool
    {
        return \PHP_VERSION_ID >= 70300;
    }
}
