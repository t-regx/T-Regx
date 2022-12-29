<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class Span
{
    /** @var string */
    private $content;
    /** @var bool */
    private $closed;

    public function __construct(string $content, bool $closed)
    {
        $this->content = $content;
        $this->closed = $closed;
    }

    public function closed(): bool
    {
        return $this->closed;
    }

    public function content(): string
    {
        return $this->content;
    }
}
