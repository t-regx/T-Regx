<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;

interface Upstream
{
    /**
     * @throws UnmatchedStreamException Supposed to be thrown
     * when subject backing the stream wasn't matched, to
     * differentiate an empty stream from a stream that was
     * empty from the beginning.
     */
    public function all(): array;

    /**
     * @throws UnmatchedStreamException Supposed to be thrown
     * when subject backing the stream wasn't matched, to
     * differentiate an empty stream from a stream that was
     * empty from the beginning.
     * @throws EmptyStreamException Supposed to be thrown
     * when the stream is empty and there is no first element,
     * for example when it was filtered out.
     * @throws StreamRejectedException Supposed to be thrown
     * when first element of the stream couldn't be resolved,
     * because of a match reason (unmatched subject or group).
     */
    public function first(): array;
}
