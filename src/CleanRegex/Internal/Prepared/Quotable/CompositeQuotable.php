<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

use function array_map;
use function implode;

class CompositeQuotable implements Quotable
{
    /** @var Quotable[] */
    private $quoteables;

    public function __construct(array $quoteables)
    {
        $this->quoteables = $quoteables;
    }

    public function quote(string $delimiter): string
    {
        return implode($this->mapToQuoteStrings($delimiter));
    }

    private function mapToQuoteStrings(string $delimiter): array
    {
        return array_map(static function (Quotable $quoteable) use ($delimiter) {
            return $quoteable->quote($delimiter);
        }, $this->quoteables);
    }
}
