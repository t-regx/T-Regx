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
     */
    public function first(): array;
}
