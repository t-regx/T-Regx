<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class PosixClass
{
    /** @var Feed */
    private $feed;
    /** @var string[] */
    private $names = [
        '[:alpha:]', '[:alnum:]', '[:ascii:]', '[:blank:]',
        '[:cntrl:]', '[:digit:]', '[:graph:]', '[:lower:]',
        '[:upper:]', '[:print:]', '[:punct:]', '[:space:]',
        '[:word:]', '[:xdigit:]',

        '[:^alpha:]', '[:^alnum:]', '[:^ascii:]', '[:^blank:]',
        '[:^cntrl:]', '[:^digit:]', '[:^graph:]', '[:^lower:]',
        '[:^upper:]', '[:^print:]', '[:^punct:]', '[:^space:]',
        '[:^word:]', '[:^xdigit:]'
    ];

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function openedBracket(): ?string
    {
        if (!$this->feed->startsWith('[:')) {
            return '[';
        }
        foreach ($this->names as $name) {
            if ($this->feed->startsWith($name)) {
                return $name;
            }
        }
        return '[';
    }
}
