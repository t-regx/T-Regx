# T-Regex Development and Contributing rules

1. Use TDD
2. Prefer exceptions to magic
3. Try to maximize type-safety
4. Try to minimize inconsistencies
5. Don't create "general-purpose" classes
6. Try to design the code clean and simple
7. Follow "Zen of Python"

! * *Magic would be: defaults, `null`s, warnings, implicit arguments or false-positives*

## Use TDD

- Prefer many small tests, instead of few big ones.
- Try to write tests starting from entry points, that is `Pattern`, `Detail`, `Group`. Try not to use classes
  from `Internal/` namespace in tests. Try not to write tests that *know* about internal classes. Treat the whole
  library as a single unit.
- Prefer creating fake implementation of interfaces for tests, instead of using dynamic mocks from PhpUnit.
- Don't try to make tests structure similar to the code structure. The two should evolve in different directions.
  There is no rule, that says each `Foo` class should have a corresponding `FooTest` class.
- Don't try to reduce duplication in tests. If two tests look similar, and test similar behaviours but yet are different
  in some way, test slightly different paths - keep both.
- Use Mutation Testing
- **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, know the
  specification, get acquainted with the assertions, understand what is being tested.
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

Don't use default arguments. They are very rarely helpfu, most of the time they are implicit hidden values. Internally,
default arguments aren't used. We allow default arguments in the entry points of the library for the convenience of the
user, such places are for example:

- In `toInt()`/`isInt()` - argument `$base`, when not passed is assigned value `10`, because of almost
  universal usage of integers in base-10.
- In `Pattern::of(pattern, $flags)` - argument `$flags` can be omitted and "no flags" is implied.

Follow The Principle of Least Astonishment.

## Type-safety

Avoid functions that allow two types of values. When faced with an urge to declare an argument as a union, please,
re-think your design. Consider two separate methods or perhaps a polymorphic/OOP design.

In T-Regx we only allow one such place, `group(string|int)`, but that's not our decision, but consequence of the fact
that groups can either be named or indexed in PHP regular expressions. To design methods `group(int)`
/`groupByName(string)` wouldn't actually make things better. Additionally `group(string|int)`/`groupExists(string|int)`
only allows multiple types as an entry point to the library. Internal implementation of groups uses `GroupKey`
abstraction, which doesn't have this ambiguity.

Most importantly - the behaviour of `group()`/`groupExists()` doesn't changed based on the type of argument. Method
`group()` simply returns `Group` based on the identifier (be it `int` or `string`). It would be unnaceptable for the
method to perform certain action when given `string` and different action when given `int`.

## Minimisation of inconsistencies

Knowing a part of the library should aid in learning other parts. That's why similar actions should have similar
effects. Example of this is that calling `matched('2group')` and `groupExists('2group')` both result in the exact same
effect of throwing `InvalidArgumentException` with exactly the same message. In fact, passing invalid group name
to any method accepting a group identifier ends in exactly the same exception.

## "General-purpose" classes

In a high enough abstraction layer, there's little room for "general purpose" classes. Don't create classes that are
unnecessarily loosely coupled, "because it may be used later". If there is a reason to truly reuse them in the future,
we'll refactor the class accordingly, when we have the reason before us. Classes should be designed with a specific
and very concrete goal in mind. The T-Regx library should expose the general purpose classes, like `Detail`, `Group` to
the user as part of the library interface, but that doesn't necessarily mean that internal library implementation
should use them.

## Simple and clean design

- Prefer immutable classes (not everytime that's possible)
- Don't add getters and setters, just for the sake of them
- Follow Zen of Python
- Try not to use T-Regx interface inside T-Regx. Exposed interface, like methods of `Pattern` or methods of `Detail`
  should be used by the client of the library alone, not the library itself.

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
