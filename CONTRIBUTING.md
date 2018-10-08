# T-Regex Development and Contributing rules

There are a few rules that we believe must be obeyed, while developing and maintaining T-Regx:
 - Lack of fake TDD
 - Lack of any inconsistencies
 - Simple and clean design
 - Lack of unnecessary performance overhead, if it can be easily avoided

## Fake TDD
In presence of overwhelming fashion for scrum teams, agile approaches, tdd, oop and what not - one must understand what T-Regx 
understands as TDD, which is not always the same as what others might consider TDD.

   - It's not **just** about writing tests before implementation
   - It's not **only** about dependency injection and ensuring a testable class
   - It's not **just** about writing additional test cases while the implemntation is being developed
   - It's not **only** about using mocks, separation of concerns, loose coupling between tests and logic, etc

 but:
   - It's **also** about being able to recognize that - if a certain testcase tests a feature of class `A` and logic of the feature is 
     delegated to class `B`, then tests for class `B` **should not** test that feature - because it's already covered by class `A` tests.
   - It's **also** about developing a feature, while understanding: 
     - **what feature** (or what element of the feature) does your test validate. It's crucial. A testcase should test only one 
       such element. Ideally, that part should only be tested **once**.
     - **why** did you write another test:
       - isn't that part already covered?
       - does the situation you're testing against have **any possibility** of occuring?
       - does this testcase test the same feature as previous test, but with different parameters?
  - It's **also** about one's discipline to not only test the Happy Paths, but also exceptions, errors and unexpected input values.

## Ideal TDD
  T-Regx contributors must take for granted that:
   - Coverage is **not** a determinant of a well tested code. One can achieve 100% coverage with little effort 
     and **still** encounter an enormous number of bugs. 
     
     Coverage means:
     - Not covered parts are **definitely** not tested
     - Covered parts are **not necessarily** tested
     
     That's it. No more information can be obtained from coverage.
   - A class that has coverage hits **is not necessarily** tested. To verify that, one should get acquainted with:
     - Unit tests of the class
     - Unit tests of clients having this class as a dependency
     - Integration testcases that test behaviour of the class/feature implemented by the class (also in an indirect way)
     
     **Only** once done that, one is able to define which parts of the class are covered by tests and which are not.
   - **There is no automatic tool that can tell you whether a file is tested.** You need to read the tests, understand what
     parts are covered and what not.
   - There is no rule that suggests that each `Abc` class should have a corresponding `AbcTest` class. 
     You should choose your tests accordingly:
     - `Unit` - There's a need to test a complicated feature of a class with a lot of cornercases? Fine - you must couple a class to a test, 
       and face the consequence that refactoring outside the class is not possible (cause you'd have to adapt to tests).
     - `Integration` - You'd like to test behaviour - then test only the part of your interface that's relevant to the feature domain,
       and not each element separately. Try to keep coupling of tests with logic as low as possible.

## Other

### Simple and clean design
We'd like you to try to avoid unnecessary setters and getters, just for the sake of them. All classes, whichever are fit for it, 
should be **immutable**. If there's a need for an object that has to persists a state, it should be extract to a dedicated class 
that's sole purpose is to maintain the state.
  
  
