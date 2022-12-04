<?php
namespace TRegx\CleanRegex\Internal\AutoCapture;

use TRegx\CleanRegex\Internal\AutoCapture\Group\IdentityGroupAutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Group\LegacyGroupAutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\ImposedNoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\ModifierAutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\OptionSettingAutoCapture;
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
            return new CompositeAutoCapture(new ModifierAutoCapture(), new IdentityGroupAutoCapture());
        }
        if (Pcre::pcre2()) {
            return new CompositeAutoCapture(new OptionSettingAutoCapture(), new IdentityGroupAutoCapture());
        }
        return new CompositeAutoCapture(new ImposedNoCapture(), new LegacyGroupAutoCapture());
    }
}
