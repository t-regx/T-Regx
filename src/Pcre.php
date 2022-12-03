<?php
namespace TRegx;

use TRegx\SafeRegex\Internal\PcreVersion;

/**
 * PHP 7.3 introduced PCRE2 - a new version of PCRE implementation.
 * Until 7.3 PCRE was used. PCRE2 is marked in PHP with 10.0 and up,
 * and PCRE is marked in PHP with versions below 10.
 *
 * With PCRE2, there also came two new *int* constants:
 * {@see PCRE_VERSION_MAJOR} and {@see PCRE_VERSION_MINOR}.
 * We decided not to use these, when PCRE was updated to PCRE2,
 * but stick to {@see PCRE_VERSION} *string* constant. The reason
 * for that, is on environments where the constant is not defined
 * (7.2 and lower), the constants can be easily defined with arbitrary
 * values, thus fooling {@see \TRegx\CleanRegex\Internal\Expression\Pcre}.
 * That wouldn't be so bad if it was only a user helper,
 * but we're using {@see Pcre::pcre2} to build proper PCRE2-sensitive
 * regexps in prepared patterns, so T-Regx must know exactly whether
 * we're in PCRE or PCRE2 in order to safely build patterns. And while
 * building patterns, we can't let {@see \TRegx\CleanRegex\Internal\Expression\Pcre}
 * helper be fooled by arbitrary constants defined by users.
 *
 * With {@see PCRE_VERSION}, there is no such risks, because it's
 * present since PHP5.6, and it's impossible to redefine it.
 */
class Pcre
{
    public static function pcre2(): bool
    {
        return self::version()->pcre2();
    }

    public static function semanticVersion(): string
    {
        return self::version()->semanticVersion();
    }

    public static function majorVersion(): int
    {
        return self::version()->majorVersion();
    }

    public static function minorVersion(): int
    {
        return self::version()->minorVersion();
    }

    private static function version(): PcreVersion
    {
        return new PcreVersion(\PCRE_VERSION);
    }
}
