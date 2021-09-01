<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class AllStreamBase extends StreamBase
{
    /** @var RawMatchesOffset */
    private $matches;

    public function __construct(RawMatchesOffset $matches)
    {
        parent::__construct(new ThrowApiBase());
        $this->matches = $matches;
    }

    public static function texts(array $texts): self
    {
        return new self(new RawMatchesOffset([\array_map(static function (string $text): array {
            return [$text, 0];
        }, $texts)]));
    }

    public static function offsets(array $offsets): self
    {
        return new self(new RawMatchesOffset([\array_map(static function (int $offset): array {
            return ['unused', $offset];
        }, $offsets)]));
    }

    public function all(): RawMatchesOffset
    {
        return $this->matches;
    }
}
