<?php
namespace TRegx\SafeRegex\Internal;

class PcreVersion
{
    /** @var string */
    private $pcreVersion;

    public function __construct(string $pcreVersion)
    {
        $this->pcreVersion = $pcreVersion;
    }

    public function pcre2(): bool
    {
        return $this->majorVersion() >= 10;
    }

    public function semanticVersion(): string
    {
        $version = \strStr($this->pcreVersion, ' ', true);
        if ($version === false) {
            return $this->pcreVersion;
        }
        return $version;
    }

    public function majorVersion(): int
    {
        return \strStr($this->pcreVersion, '.', true);
    }

    public function minorVersion(): int
    {
        [$major, $minor] = \explode('.', $this->semanticVersion());
        return $minor;
    }
}
