<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>

# T-Regx | Regular Expressions library

Simple library for regular expressions in PHP.

[![OS Arch](https://img.shields.io/badge/OS-32&hyphen;bit-brightgreen.svg)](https://github.com/t-regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-64&hyphen;bit-brightgreen.svg)](https://github.com/t-regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Windows-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Linux/Unix-blue.svg)](https://github.com/t-regx/T-Regx/actions)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://github.com/t-regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://github.com/t-regx/T-Regx/actions)

1. [Installation](#installation)
    * [Composer](#installation)
2. [Examples](#examples)

9. [Sponsors](#sponsors)
10. [License](#license)

[Buy me a coffee!](https://www.buymeacoffee.com/danielwilkowski)

# Installation

You can install the alpha version:

```bash
composer require rawr/t-regx@1.0.1-alpha
```

# Examples

Predicate a string against a regular expression:
```php
$pattern = new Pattern('[a-z]', 'i');  // no need for /delimiters/
if ($pattern->test($string)) {

}
```

Details about match (text, groups, offset):

```php
$pattern = new Pattern('(?<group>[a-z])', 'i');

/** @var Detail $match */
$match = $pattern->first($words);  // execute regular expression

$match->text();          // (string) matched text
$match->group(0);        // (string) matched text

$match->group(1);        // (string) capturing group
$match->groupOrNull(1);  // (string) capturing group

$match->group('group');  // capturing group by name

$match->offset();        // (int) match position (in unicode characters)
$match->byteOffset();    // (int) match position (in bytes/ascii)
```

Perform global regular expression search:

```php
$pattern = new Pattern('\w+');

$pattern->search('Foo, Bar, Cat'); // (string[]) ['Foo', 'Bar', 'Cat']
```

Perform global regular expression search, returning only a capturing group:
```php
$pattern = new Pattern(':(\w+):');

$pattern->searchGroup(':Foo: :Bar: :Cat:', 1); // (string[]) ['Foo', 'Bar', 'Cat']
```

Split string by regular expression:

```php
$pattern = new Pattern('[,; ]');

$pattern->split("Valar Morghulis"); // ["Valar", "Morghulis"]
```

Split string by regular expression, with max splits:

```php
$pattern = new Pattern(', ?');
[$father, $mother, $maiden, ...$rest] = $pattern->split(
    'Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 
    maxSplits:3
  ));
```

Replace by regular expression:

```php
$pattern = new Pattern('\d+');

$pattern->replace("I have $3", '10');
$pattern->replaceCallback("I have $3", fn(Detail $match) => '10');
```

Filter a string array:

```php
$pattern = new Pattern('\d+');

$pattern->filter(['Foo', '12', 'word', '14']); // (string[]) ['12', '14']
$pattern->reject(['Foo', '12', 'word', '14']); // (string[]) ['Foo', 'word']
```

Count occurrences:

```php
$pattern = new Pattern('\d+');

$pattern->count('12, 14, 15'); // (int) 3
```

# Matcher

```php
$pattern = new Pattern('\d+');

$matcher = $pattern->match('12, 14, 15, 16'); // performs global regular expression match

// everything below doesn't execute matching again

$matcher->all();          // returns Detail[]
$matcher->first();        // returns the first Detail, or throws NoMatchException
$matcher->firstOrNull();  // returns the first Detail or null

/** @var Detail $match */
foreach ($matcher as $match) { // iterate matcher

}
```

# Modifiers

```php
$pattern = new Pattern('[a-z]', modifiers:'i'); // string
$pattern = new Pattern('[a-z]', Pattern::IGNORE_CASE); // more verbose
```

Even though `n` is only available since PHP 8.2, with T-Regx `n` is available in all versions, due to backporting:

```php
$pattern = new Pattern('[a-z]', modifiers:'n'); // works on any PHP version
```

## Comments and whitespace

Use `Pattern::COMMENTS_WHITESPACE` to construct readable expressions

```php
$urlPattern = new Pattern('
  https?://    # scheme
  (www\.)?     # optional www.
  \w+          # domain name
  \.(com|org)  # top-level
  /            # path separator
  \w*          # remaining path
', Pattern::COMMENTS_WHITESPACE);

$urlPattern->test('http://www.github.com/'); // (bool) true
```

# Error handling

```php
new Pattern('€[ą-ęA-Z]+++', 'u')
```
```
Regex\SyntaxException : Quantifier does not follow a repeatable item, near position 15.

'€[ą-ęA-Z]+++'
            ^
```

Catastrophic backtracking:

```php
$pattern = new Pattern('(\d+\d+)+3');
$pattern->test('11111111111111111111 3');
```
```
Regex\BacktrackException : Catastrophic backtracking occurred when matching the subject.
```

## Group errors

Existing groups and format validation:
```php
$pattern = new Pattern('(\w+)');
/** @var Detail $match */
$match = $pattern->first('word');

$match->group(2); // throws GroupException, because there is no group 2
$match->group('2group'); // throws \InvalidArgumentException, because name '2group' is not a valid group name
```

Unmatched groups:

```php
$pattern = new Patter('(Foo)(Bar)?');

/** @var Detail $match */
$match = $pattern->first('Foo');

$match->group(2);        // throws GroupException, because the group is not matched
$match->groupOrNull(2);  // returns null
```

## How to handle errors

In normal situations, these errors shouldn't occur, but if you really need to handle them do:
```php
try {
    $pattern = new Pattern('(\d+\d+)+3');
} catch (PatternException $e) {
}

try {
    $pattern->test('11111111111111111111 3');
} catch (MatchException $exception) {
}
```

or if you need to be more granural:
```php
try {
    $pattern = new Pattern('(\d+\d+)+3');
} catch (SyntaxException $e) {
} catch (ExecutionException $e) {
}

try {
    $pattern->test('11111111111111111111 3');
} catch (BacktrackException $exception) {
} catch (RecursionException $exception) {
} catch (JitException $exception) {
} catch (UnicodeException $exception) {
}
```

To catch *anything*, do `catch (RegexException) {}`.

Additionally, there are exceptions that really should never happen:

```php
try {
  new Pattern('\w+', 'k'); // there is no modifier 'k' in regexps
} catch (ModifierException $e) {
}
```

## Partial matches

With `Pattern.matchPartial()` you can get matches before engine hits an error:

```php
$pattern = new Pattern('(\d+\d+)+3');

$subject = '123, 543, 11111111111111111111 3';  // only two occurrences can be matched, before hitting catastrophic backtrack error

/** @var Detail $match */
foreach ($pattern->matchPartial($subject) as $match) {
    echo "I found: " . $match->text() . PHP_EOL;
}
```
```
I found: 123
I found: 543

Regex\BacktrackException : Catastrophic backtracking occurred when matching the subject.
```

You can stop in time if you want:
```php
foreach ($pattern->matchPartial($subject) as $match) {
    echo "I found: " . $match->text() . PHP_EOL;
    if ($match->text() === '543') {
      break;
    }
}
```
```
I found: 123
I found: 543
```

If you call any other method, it throws `BacktrackException` right away.

## Unicode errors

In pattern
```php
new Pattern("[a-z] \xe2\x28\xa1", 'u');
```
```
Regex\UnicodeException : Malformed regular expression, byte 2 top bits not 0x80, near position 12.
```

or in subject:
```php
$pattern = new Pattern('\w+', 'u');
$pattern->test("\xe2\x28\xa1");
```
```
Regex\UnicodeException : Malformed unicode subject.
```

## Inspect groups

```php
$pattern = new Pattern('(foo)(?<group>bar)');

$pattern->groupExists(2); // (bool) true
$pattern->groupExists(3); // (bool) false

$pattern->groupExists('group'); // (bool) true

$pattern->groupNames(); // (array) [null, 'group']
```

```php
$pattern = new Pattern('(foo)(?<group>bar)?');
$match = $pattern->first('foobar');

$match->groupMatched('group'); // (bool) true, group is matched
```

# Integration

You can use `Pattern` with vanilla `preg_*()` if you want:

```php
$pattern = new Pattern('(\w+)(?<group>foo)', 'n');

\preg_replace_callback("$pattern", $callback, $subject);
```

Or other way around:

```php
$pattern = new PregPattern('/\w+/i');

// both Pattern and PregPattern implement Regex
$pattern->test('string');
$pattern->search('string');
$pattern->match('string');
$pattern->first('string');
```

# Plans for the future

I'm planning on creating a helper-package `t-regx/functions`, which would introduce helper-functions:

```php
(new Pattern('\w'))->test($string);   // instead of this
re_test('\w', $string);               // we could have this
                                      // "re" stands for "regular expression"
```

Let me know what you think in the Discussions!

# Sponsors

- [Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)
- [BarxizePL](https://github.com/BarxizePL) - Thanks!

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License

T-Regx is [MIT licensed](LICENSE).
