# T-Regex Development and Contributing rules

There are a few rules that we believe must be obeyed, while developing and maintaining T-Regx:
 - Lack of fake TDD
 - Lack of any inconsistencies
 - Simple and clean design
 - Lack of unnecessary performance overhead, if it can be easily avoided

## Fake TDD
In presence of overwhelming fashion for scrum teams, agile approaches, tdd, oop and what not - one must understand what 
T-Regx understands as TDD, which is not always the same as what others might consider TDD.

   - It's not **just** about writing tests before implementation
   - It's not **only** about dependency injection and ensuring a testable class
   - It's not **just** about writing additional test cases while the implementation is being developed
   - It's not **only** about using mocks, separation of concerns, loose coupling between tests and logic, etc

Keep in mind, that:
   - It's **also** about being able to recognize that - if a certain testcase tests a feature of class `A` and logic of 
     the feature is delegated to class `B`, then tests for class `B` **should not** test that feature - because it's 
     already covered by class `A` tests. Any additional, specific, corner-cases test are free to go into `B`, but not 
     copies of that test.
   - It's **also** about developing a feature, while understanding: 
     - **what feature** (or what element of the feature) does your test validate. It's crucial. A testcase should test 
        only one such element. Ideally, that part should only be tested **once**. It should be tested **well**.
     - **why** did you write another test:
       - isn't that part already covered?
       - are you testing an interface of a class?
       - does this testcase test the same feature as previous test, but with different parameters?
  - It's **also** about one's discipline to not only test the Happy Paths, but also exceptions, errors and wide variety 
    of input values.

Fake TDD can be easily spotted by slightly altering a single value in a codebase (changing `true` to `false`, commenting 
a line out, replacing `if` conditions with `if(true)`). If all the tests still pass after the change - the edited file 
wasn't written with TDD in mind.

## Ideal TDD
  T-Regx contributors must take for granted that:
   - Coverage is **not** a determinant of a well tested code. One can achieve 100% coverage with little effort 
     and **still** encounter an enormous number of bugs. 
     
     Coverage means:
     - Not covered parts are **definitely** not tested
     - Covered parts are **not necessarily** tested
     
     That's it. No more information can be obtained from coverage.
   - A class containing coverage hits **is not necessarily** tested. To verify that, one should get acquainted with:
     - Unit tests of the class
     - Unit tests of clients having this class as a dependency
     - Integration testcases that test behaviour of the class/feature implemented by the class (also indirectly)
     - Mutation testing
     
     **Only** once done that, one is able to define which parts of the class are covered by tests and which are not.
   - **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, understand
     what parts are covered and what not.
   - There is no rule, so that each `Foo` class should have a corresponding `FooTest` class. 
     You should choose your tests accordingly:
     - `Interaction` - You'd like to test behaviour - then test only the part of your interface that's relevant to the 
       feature domain, and not each element separately - write the smallest tests possible. Try to keep coupling of 
       tests with logic as low as possible.
       
       Rules: 
       - If possible, any dependencies should be real instances, instead of mocks. If instantiating becomes too 
         complicated, occasional mocks are allowed, to make tests easier to read and edit.
     
     - `Feature` (a.k.a. "end to end") - This category has two goals:
       - Ensure that a certain functionality works "out of the box" (as if used by an end user).
       - Ensure that each dependency is integrated properly with other dependencies.
      
       Rules:
       - There should be as least tests in this category as possible (preferably 1 per functionality). More throughout 
         testcases should be in `Unit` or `Integration`.
       - Each test-case must be created from `pattern()` function or one of `Pattern` factory methods.
     - `Unit` - There's a need to test a complicated feature of a class with a lot of corner-cases? 
     
       Fine - you must couple a class to a test, and face the consequence that refactoring outside the class is not 
       possible (cause you'd also have to adapt unit tests).
       
       Rules:
       - 100% of dependencies must be mocked. Only a change in the tested class can cause the test to fail.
     - `Functional` - The name may be a bit misleading, tests in `Functional` cover T-Regx's contract with PHP methods, 
        errors, warnings, etc.
     
       Rules:
       - Tests for breaking changes between PHP versions should be in `Functional`
       - Tests for `preg_` and `preg::` should be in `Functional`
       
## Other

### Avoid inconsistencies
We value consistency. Knowing a part of a library, should aid in learning the rest of it. 

Keep in mind:
 - When editing a feature, see if there's also a similar feature. Consider, whether they both should be edited, or only 
   the one?
 - For example, at first, there were only methods `flatMap()` in `match()`, `match()->group()` and others. When we added
   `flatMapAssoc()`, we didn't only add it to the most obvious group, but everywhere where `flatMap()` was.

### Simple and clean design
We'd like you to try to avoid unnecessary setters and getters, just for the sake of them. All classes, whichever are fit 
for it, should be **immutable**. If there's a need for an object that has to persist state, it should be extracted to a 
dedicated class that's sole purpose is to maintain the state.
  
### Formality
We use Fully Qualified Names for global PHP functions, for the sake of performance.
To keep it consistent, we also added a composer command which helps us find any unqualified functions. Use it like so
```bash
./composer.phar fqn
```
or
```cmd
php composer.phar fqn
```
