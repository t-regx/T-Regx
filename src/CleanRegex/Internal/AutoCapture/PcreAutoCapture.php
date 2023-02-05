<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\Pcre;

class PcreAutoCapture
{
    /** @var AutoCapture */
    private static $autoCapture;

    public static function autoCapture(): AutoCapture
    {
        if (self::$autoCapture === null) {
            self::$autoCapture = self::instance();
        }
        return self::$autoCapture;
    }

    private static function instance(): AutoCapture
    {
        if (\PHP_VERSION_ID >= 80200) {
            return new IdentityAutoCapture();
        }
        if (Pcre::pcre2()) {
            return new OptionSettingAutoCapture();
        }
        return new ImposedAutoCapture();
    }
}
