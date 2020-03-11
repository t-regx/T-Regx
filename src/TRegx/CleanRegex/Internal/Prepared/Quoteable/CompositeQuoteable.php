<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

use function array_map;
use function implode;

class CompositeQuoteable implements Quoteable
{
    /** @var Quoteable[] */
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
        return array_map(function (Quoteable $quoteable) use ($delimiter) {
            return $quoteable->quote($delimiter);
        }, $this->quoteables);
    }
}
