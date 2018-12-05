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
        $strings = $this->mapToQuoteStrings($delimiter);
        return implode($strings);
    }

    /**
     * @param string $delimiterer
     * @return array
     */
    private function mapToQuoteStrings(string $delimiterer): array
    {
        return array_map(function (Quoteable $quoteable) use ($delimiterer) {
            return $quoteable->quote($delimiterer);
        }, $this->quoteables);
    }
}
