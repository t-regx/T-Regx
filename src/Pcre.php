<?php
namespace TRegx;

/**
 * PHP 7.3 introduced PCRE2 - a new PCRE version. Until 7.3
 * PCRE was used. "PCRE2" is what PHP calls PCRE 10.0 and up,
 * and "PCRE" is what PHP calls PCRE version below 10. It probably
 * calls them PCRE/PCRE2, because Pcre 10.0 introduced a lot
 * of breaking changes, that we must react to in T-Regx.
 * With PCRE2, there also came two new *int* constants:
 * {@see PCRE_VERSION_MAJOR} and {@see PCRE_VERSION_MINOR}.
 *
 * We decided not to use these, when PCRE was updated to PCRE2,
 * but stick to {@see PCRE_VERSION} *string* constant. The reason
 * for that, is on environments where the constant is not defined
 * (7.2 and lower), the constants can be easily defined with arbitrary
 * values, thus fooling this {@see Pcre} helper. That wouldn't be
 * so bad if it was only a user helper, but we're using {@see Pcre::pcre2} to
 * build proper PCRE2-sensitive regexps in prepared patterns,
 * so T-Regx must know exactly whether we're in PCRE or PCRE2
 * in order to safely build patterns. And while building patterns,
 * we can't {@see Pcre} helper be fooled by arbitrary constants
 * defined by users.
 *
 * With {@see PCRE_VERSION}, there is no such risks, because it's
 * present since PHP5.6, and it's impossible to redefine it.
 */
class Pcre
{
    public static function pcre2(): bool
    {
        return self::majorVersion() >= 10;
    }

    public static function semanticVersion(): string
    {
        [$semanticVersion, $date] = \explode(' ', \PCRE_VERSION);
        return $semanticVersion;
    }

    public static function majorVersion(): int
    {
        [$major, $minor] = \explode('.', self::semanticVersion());
        return $major;
    }

    public static function minorVersion(): int
    {
        [$major, $minor] = \explode('.', self::semanticVersion());
        return $minor;
    }
}
