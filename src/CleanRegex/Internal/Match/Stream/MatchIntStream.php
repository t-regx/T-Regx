<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Integer;

class MatchIntStream implements Stream
{
    /** @var StreamBase */
    private $stream;

    public function __construct(StreamBase $stream)
    {
        $this->stream = $stream;
    }

    public function all(): array
    {
        return \array_map([$this, 'parseInteger'], $this->stream->all()->getTexts());
    }

    public function first(): int
    {
        return $this->parseInteger($this->stream->first()->getText());
    }

    private function parseInteger(string $text): int
    {
        if (Integer::isValid($text)) {
            return $text;
        }
        throw IntegerFormatException::forMatch($text);
    }

    public function firstKey(): int
    {
        $this->stream->first()->getText();
        return $this->stream->firstKey();
    }
}
