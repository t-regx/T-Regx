<?php
namespace Test\Supposition\lineEndings;

class EndingsMap
{
    /** @var string[] */
    protected $endings;

    public function __construct()
    {
        $this->endings = [
            'tab'  => "\t", // T  - Tab
            'lf'   => "\n", // LF - Line-Feed
            'vt'   => "\v", // VT - Vertical-tab
            'ff'   => "\f", // FF - Form feed
            'cr'   => "\r", // CR - Carrige Return
            'crlf' => "\r\n", // CRLF
            'lfcr' => "\n\r", // LFCR
            'nl'   => "\xC2\x85", // NEL - Next Line
            'ls'   => "\xE2\x8a\xA8", // LS - Line separator
            'ps'   => "\xE2\x8a\xA9", // PS - Paragraph Separator
        ];
    }

    public function ending(string $name): string
    {
        return $this->endings[$name];
    }

    public function names(): array
    {
        return \array_keys($this->endings);
    }
}
