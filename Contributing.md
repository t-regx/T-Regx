# T-Regex Development and Contributing rules

1. The **the** importantest - **Lack of fake TDD**
2. The importantest - **Prefer exceptions to magic***
3. Very important - **Type-safety**
4. Much important - **Minimisation of inconsistencies**
5. Important - **No "general-purpose" classes**
6. Yes - **Simple and clean design**

! * *Magic would be: defaults, `null`s, warnings, implicit arguments or false-positives*

## Fake TDD

T-Regx contributors must take for granted that:

- There is no rule, that says each `Foo` class should have a corresponding `FooTest` class. Surprising, I know.
- **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, understand the
  specification.
- Coverage is **not** a determinant of well tested code. One can achieve 100% coverage with little effort and **still**
  encounter an enormous number of bugs.

  Coverage means:
  - Not covered parts are **definitely** not tested
  - Covered parts are **not necessarily** tested

  A class containing coverage hits **is not necessarily** tested. To verify that, one should get acquainted with: unit
  tests, interaction testcases that test behaviour of the feature directly or indirectly. Or using mutation testing.

## Exceptions over magic

When class/function should do two different things, based on circumstances, when given choice between returning `null`
and throwing an exception, throw an exception. If you're given a choice between returning `-1` as a sign of "index not
found", and throwing an exception - throw an exception. If you're given a choice between returning a value that might be
correct in 99% cases but is actually a false positive in 1% cases - throw an exception.

## Type-safety

Avoid functions that allow two types of values. When faced with an urge to declare an argument as a union, please,
re-think your design. Consider two separate methods or perhaps a polymorphic/OOP design.

In T-Regx we only allow one such place, `group(string|int)`, but that's not our decision, but consequence of the fact
that groups can either be named or indexed in PHP regular expressions. To design methods `group(int)`
/`groupByName(string)` wouldn't actually make things better. Additionally `group(string|int)`/`hasGroup(string|int)`
only allows multiple types as an entry point to the library. Internal implementation of groups uses `GroupKey`
abstraction, which doesn't have this ambiguity.

## Minimisation of inconsistencies

Knowing a part of the library should aid in learning other parts. That's why similar actions should have similar
effects. Example of this is that calling `matched('2group')` and `hasGroup('2group')` both result in the exact same
effect of throwing `InvalidArgumentException()` with exactly the same message. For them to react in different ways to
the same issue would increase inconsistencies, yet we need to minimize them. Another example is the fact, that replacing
by an unmatched group throws `GroupNotMatchedException`, so replacing with map by an unmatched group must also result in
`GroupNotMatchedException`. If regular `replace()` provides `with()`/`withReferences()` abstraction
over `preg_replace()`, so must the focused replace, even though there is no such API in PHP, and we must provide it. If
it was impossible to spoof the implementation, so must the `replace()` not have had that functionality; in order to
minimize the inconsistencies.

## "General-purpose" classes

In a high enough abstraction layer, there's little room for "general purpose" classes. Don't create classes that are
unnecessarily loosely coupled, "because it may be used later". If there is a reason to truly reuse them in the future,
we'll refactor the class accordingly, when we have the reason before us.

## Simple and clean design

- Prefer immutable classes (not everytime that's possible)
- Don't add getters and setters, just for the sake of them
- Follow Zen of Python

## Formality

### Fully qualified names

We use Fully Qualified Names for global PHP functions, for the sake of performance. To keep it consistent, we also added
a composer command which helps us find any unqualified functions. Use it like so

```bash
./composer.phar fqn
```

or

```cmd
php composer.phar fqn
```
