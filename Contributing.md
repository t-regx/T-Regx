# T-Regex Development and Contributing rules

1. Use TDD
2. Prefer exceptions to magic
3. Try to maximize type-safety
4. Try to minimize inconsistencies
5. Don't create "general-purpose" classes
6. Try to design the code clean and simple
7. Follow "Zen of Python"
8. Handle each supported PHP version
9. Formality

! * *Magic would be: defaults, `null`s, warnings, implicit arguments or false-positives*

## Use TDD

- Prefer many small tests, instead of few big ones.
- Try to write tests starting from entry points, that is `Pattern`, `Detail`, `Group`. Try not to use classes
  from `Internal/` namespace in tests. Try not to write tests that *know* about internal classes. Treat the whole
  library as a single unit.
- Prefer creating fake implementation of interfaces for tests, instead of using dynamic mocks from PhpUnit.
- Don't try to make tests structure similar to the code structure. The two should evolve in different directions.
  There is no rule, that says each `Foo` class should have a corresponding `FooTest` class.
- Don't try to reduce duplication in tests. If two tests look similar, and test similar behaviours but yet are
  different in some way, test slightly different paths - keep both.
- Use Mutation Testing
- **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, know the
  specification, get acquainted with the assertions, understand what is being tested.
- Coverage is **not** a determinant of well tested code. One can achieve 100% coverage with little effort and **still**
  encounter an enormous number of bugs.

  Coverage means:
  - Not covered parts are **definitely** not tested
  - Covered parts are **not necessarily** tested

  A class containing coverage hits **is not necessarily** tested. To verify that, one should get acquainted with tests
  executing the particular part of the code or by using mutation testing.

## Exceptions over magic

When class/function should do two different things, based on circumstances, when given choice between returning `null`
and throwing an exception, throw an exception. If you're given a choice between returning `-1` as a sign of "index not
found", and throwing an exception - throw an exception. If you're given a choice between returning a value that might
be correct in 99% cases but is actually a false positive in 1% cases - throw an exception.

Don't use default arguments. They are rarely helpful, most of the time they are implicit hidden values. Internally,
default arguments aren't used. We allow default arguments in the entry points of the library for the convenience of
the user, such places are for example:

- In `toInt()`/`isInt()` - argument `$base`, when not passed is assigned value `10`, because of almost
  universal usage of integers in base-10.
- In `Pattern::of(pattern, $flags)` - argument `$modifiers` can be omitted and "no modifiers" is implied.

Follow The Principle of Least Astonishment.

## Type-safety

Avoid functions that allow two types of values. When faced with an urge to declare an argument as a union, please,
re-think your design. Consider two separate methods or perhaps a polymorphic/OOP design.

In T-Regx we only allow one such place, `group(string|int)`, but that's not our decision, but consequence of the fact
that groups can either be named or indexed in PHP regular expressions. To design methods `group(int)`
/`groupByName(string)` wouldn't actually make things better. Additionally `group(string|int)`/`groupExists(string|int)`
only allows multiple types as an entry point to the library. Internal implementation of groups uses `GroupKey`
abstraction, which doesn't have this ambiguity. Had PHP supported method overloading, then it would be a whole
different story.

Most importantly - the behaviour of `group()`/`groupExists()` doesn't changed based on the type of argument. Method
`group()` simply returns `Group` based on the identifier (be it `int` or `string`). It would be unacceptable for the
method to perform certain action when given `string` and different action when given `int`.

## Minimisation of inconsistencies

Knowing a part of the library should aid in learning other parts. That's why similar actions should have similar
effects. Example of this is that calling `matched('2group')` and `groupExists('2group')` both result in the exact
same effect of throwing `InvalidArgumentException` with exactly the same message. In fact, passing invalid group
name to any method accepting a group identifier ends in exactly the same exception.

## "General-purpose" classes

In a high enough abstraction layer, there's little room for "general purpose" classes. Don't create classes that are
unnecessarily loosely coupled, "because it may be used later". If there is a reason to truly reuse them in the future,
we'll refactor the class accordingly, when we have the reason before us. Classes should be designed with a specific
and very concrete goal in mind. The T-Regx library should expose the general purpose classes, like `Detail`, `Group`
to the user as part of the library interface, but that doesn't necessarily mean that internal library implementation
should use them.

## Simple and clean design

- Prefer immutable classes (not everytime that's possible)
- Don't add getters and setters
- Follow Zen of Python
- Try not to use T-Regx interface inside T-Regx. Exposed interface, like methods of `Pattern` or methods of `Detail`
  should be used by the client of the library, not the library itself.

## Handle each supported PHP version

T-Regx library should work exactly the same on all supported PHP versions, currently they span from: 7.1 to 8.1.
Granted, different functionalities and rules are available each PHP versions, for example `PREGPREG_UNMATCHED_AS_NULL`
for replacing is only available for PHP 7.4. In that case, other internal implementation must be chosen to provide
the functionality. From the perspective of the client of T-Regx library, it shouldn't be visible which internal
implementation was chosen.

## Keep the library hermetic

Don't let the internal implementation of PHP `preg_()` functions leak to the interface of the library. The whole point
of the library is to abstract the usage of regular expressions away.

Most notably, don't let the structure of `array` from `preg_match_all()` leak into the interface of T-Regx library.
The rationale behind it, is that this function returns `array` structure that is actually different between PHP
versions, and changes based on the order of groups and flags used. This is a very poor design, and we can't allow
T-Regx library to suffer the same flaw. What should be done instead, is proper parsing and interpretation of the `array`
structure of `preg_match_all()`, and exposing the read data via properly designed interface, such as `Detail` or `Group`.

Currently, we only allow one such place:

- `Replace.withReferences()` - currently, `withReferences()` accepts group-reference format from `preg_replace()`.
  That means, that any change to the reference format in the `preg_replace()`, would (currently) change the interface
  of T-Regx library. While this isn't ideal (`callback()`/`with()` are the recommended choice), that's the current
  implementation. We didn't observe the preg reference template to change between PHP versions, so it's relatively
  stable.

  Probably, in the future, `withReferences()` should be improved, so that T-Regx parses the template into
  T-Regx-format and formats the group internally, instead of relying on `preg_replace()` format.

## Formality

### Fully qualified names

We use Fully Qualified Names for global PHP functions, for the sake of performance. To keep it consistent, we also
added a composer command which helps us find any unqualified functions. Use it like so

```bash
./composer.phar fqn
```

or

```cmd
php composer.phar fqn
```
