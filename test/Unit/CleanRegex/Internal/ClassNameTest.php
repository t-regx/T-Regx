<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use Error;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\AbstractClass;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithErrorInConstructor;
use Test\Utils\ClassWithoutSuitableConstructor;
use Test\Utils\ClassWithStringParamConstructor;
use Test\Utils\ClassWithTwoStringParamsConstructor;
use Throwable;
use TRegx\CleanRegex\Exception\ClassExpectedException;
use TRegx\CleanRegex\Exception\NoSuitableConstructorException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\ClassName;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @covers \TRegx\CleanRegex\Internal\ClassName
 */
class ClassNameTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNotExistingClass()
    {
        // given
        $className = new ClassName('Namespace\NoSuchClass');

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage('Class \Namespace\NoSuchClass does not exists');

        // when
        $className->throwable(new FirstMatchMessage(), new ThrowSubject());
    }

    /**
     * @test
     */
    public function shouldThrow_onInterface()
    {
        // given
        $className = new ClassName(Throwable::class);

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage('\Throwable is not a class, but an interface');

        // when
        $className->throwable(new FirstMatchMessage(), new ThrowSubject());
    }

    /**
     * @test
     */
    public function shouldThrow_onAbstractClass()
    {
        // given
        $className = new ClassName(AbstractClass::class);

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage('\Test\Utils\AbstractClass is an abstract class');

        // when
        $className->throwable(new FirstMatchMessage(), new ThrowSubject());
    }

    /**
     * @test
     */
    public function shouldThrow_onClassThatIsNotThrowable()
    {
        // given
        $className = new ClassName(stdClass::class);

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage('Class \stdClass is not throwable');

        // when
        $className->throwable(new FirstMatchMessage(), new Subject('subject'));
    }

    /**
     * @test
     */
    public function shouldThrow_onClassWithoutSuitableConstructor()
    {
        // given
        $className = new ClassName(ClassWithoutSuitableConstructor::class);

        // then
        $this->expectException(NoSuitableConstructorException::Class);
        $this->expectExceptionMessage("Class 'Test\Utils\ClassWithoutSuitableConstructor' doesn't have a constructor with supported signature");

        // when
        $className->throwable(new FirstMatchMessage(), new Subject('subject'));
    }

    /**
     * @test
     */
    public function shouldRethrow_errorWhileInstantiating()
    {
        // given
        $className = new ClassName(ClassWithErrorInConstructor::class);

        // then
        $this->expectException(Error::class);
        $this->expectExceptionMessage('error message');

        // when
        $className->throwable(new FirstMatchMessage(), new Subject('subject'));
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageAndSubjectParams()
    {
        // given
        $class = new ClassName(ClassWithTwoStringParamsConstructor::class);

        // when
        $throwable = $class->throwable(new FirstMatchMessage(), new Subject('lorem ispum'));

        // then
        $this->assertInstanceOf(ClassWithTwoStringParamsConstructor::class, $throwable);
        $this->assertSame('Expected to get the first match, but subject was not matched', $throwable->getMessage());
        $this->assertSame('lorem ispum', $throwable->getSubject());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageParam()
    {
        // given
        $className = new ClassName(ClassWithStringParamConstructor::class);

        // when
        $exception = $className->throwable(new FirstMatchMessage(), new Subject('subject'));

        // then
        $this->assertInstanceOf(ClassWithStringParamConstructor::class, $exception);
        $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $className = new ClassName(ClassWithDefaultConstructor::class);

        // when
        $exception = $className->throwable(new FirstMatchMessage(), new Subject('subject'));

        // then
        $this->assertInstanceOf(ClassWithDefaultConstructor::class, $exception);
    }

    /**
     * @test
     * @dataProvider exceptions
     * @param string $classNameString
     */
    public function shouldInstantiate_withMessage(string $classNameString)
    {
        // given
        $className = new ClassName($classNameString);

        // when
        $exception = $className->throwable(new FirstMatchMessage(), (new Subject('subject')));

        // then
        $this->assertInstanceOf($classNameString, $exception);
        $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    public function exceptions(): array
    {
        return [
            [Exception::class],
            [InvalidArgumentException::class],
            [SubjectNotMatchedException::class],
        ];
    }
}
