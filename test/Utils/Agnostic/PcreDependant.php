<?php
namespace Test\Utils\Agnostic;

use PHPUnit\Framework\Assert;
use Test\Utils\Iterables;
use TRegx\Pcre;

trait PcreDependant
{
    public function onPcre2(array $dataProvider): array
    {
        if (Pcre::pcre2()) {
            return $dataProvider;
        }
        return [];
    }

    public function pcreDependentStructure(array $pcre1Patterns, array $pcre2Patterns): array
    {
        $this->assertStructuresCompatible(array_values($pcre1Patterns), array_values($pcre2Patterns));
        return $this->pcreDependant($pcre1Patterns, $pcre2Patterns);
    }

    private function pcreDependant(array $pcre1Patterns, array $pcre2Patterns): array
    {
        /**
         * Method {@see PcreDependant::isPcre2} is used intentionally here, instead
         * of {@see Pcre::pcre2}. Should {@see Pcre::pcre2} be used here, if there
         * is a bug in {@see Pcre} or any PCRE-related code, then these tests would
         * become unable to find that bug. By using {@see Pcre} in production code
         * and {@see PHP_VERSION_ID} directly in the test, it gurantees that these
         * tests remain unrelated, aren't coupled to each other and are capable of
         * finding bugs.
         */
        if ($this->isPcre2()) {
            return $pcre2Patterns;
        }
        return $pcre1Patterns;
    }

    private function assertStructuresCompatible(array $array1, array $array2): void
    {
        foreach (Iterables::zip($array1, $array2) as [[$item1], [$item2]]) {
            Assert::assertSame($item1, $item2);
        }
    }

    public function isPcre2(): bool
    {
        return \PHP_VERSION_ID >= 70300;
    }
}
