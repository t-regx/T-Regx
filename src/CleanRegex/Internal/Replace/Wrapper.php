<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Match\Detail;

/**
 * Currently, this class only purpose is to provide additional wrapper/decorator
 * over {@see GroupMapper}. Normally, {@see GroupMapper} was designed to map values,
 * and pass it to other {@see GroupMapper}s.
 *
 * But unfortunately, {@see CompositeReplacePattern::focus} function requires some
 * actions done before some mappers and after other mappers (for example before
 * {@see DictionaryMapper}, to see if focused group is matched, and also after
 * {@see DictionaryMapper}, to focus on the value returned by it). So special "kind"
 * of mapper had to be designed, that could map something *before* other mappers,
 * and then after all other mappers.
 *
 * The same could also be achieved by creating an abstract factory, in form:
 *  - AbstractFactory
 *   - IdentityFactory
 *   - FocusingFactory
 * which would create
 *  - Wrapper
 *   - IdentityWrapper
 *   - FocusingWrapper
 * so 6 new entities.
 */
interface Wrapper
{
    public function wrap(Wrappable $wrappable, Detail $initialDetail): ?string;
}
