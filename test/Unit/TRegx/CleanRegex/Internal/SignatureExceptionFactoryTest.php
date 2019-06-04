<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use Error;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\Utils\AbstractClass;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithErrorInConstructor;
use Test\Utils\ClassWithoutSuitableConstructor;
use Test\Utils\ClassWithStringParamConstructor;
use Test\Utils\ClassWithSubjectableConstructor;
use Test\Utils\ClassWithTwoStringParamsConstructor;
use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\ClassExpectedException;
use TRegx\CleanRegex\Exception\CleanRegex\NoSuitableConstructorException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\SubjectableImpl;

class SignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNotExistingClass()
    {
        // given
        $factory = new SignatureExceptionFactory('Namespace\NoSuchClass', new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'Namespace\NoSuchClass' does not exists");

        // when
        $factory->create(new SubjectableImpl(''));
    }

    /**
     * @test
     */
    public function shouldThrow_onInterface()
    {
        // given
        $factory = new SignatureExceptionFactory(Throwable::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("'Throwable' is not a class, but an interface");

        // when
        $factory->create(new SubjectableImpl(''));
    }

    /**
     * @test
     */
    public function shouldThrow_onAbstractClass()
    {
        // given
        $factory = new SignatureExceptionFactory(AbstractClass::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("'Test\Utils\AbstractClass' is an abstract class");

        // when
        $factory->create(new SubjectableImpl(''));
    }

    /**
     * @test
     */
    public function shouldThrow_onClassThatIsNotThrowable()
    {
        // given
        $factory = new SignatureExceptionFactory(stdClass::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'stdClass' is not throwable");

        // when
        $factory->create(new SubjectableImpl(''));
    }

    /**
     * @test
     */
    public function shouldThrow_onClassWithoutSuitableConstructor()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithoutSuitableConstructor::class, new FirstMatchMessage());

        // then
        $this->expectException(NoSuitableConstructorException::Class);
        $this->expectExceptionMessage("Class 'Test\Utils\ClassWithoutSuitableConstructor' doesn't have a constructor with supported signature");

        // when
        $factory->create(new SubjectableImpl(''));
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageAndSubjectParams()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithTwoStringParamsConstructor::class, new FirstMatchMessage());
        $subject = new SubjectableImpl('my subject');

        // when
        /** @var ClassWithTwoStringParamsConstructor $exception */
        $exception = $factory->create($subject);

        // then
        $this->assertInstanceOf(ClassWithTwoStringParamsConstructor::class, $exception);
        $this->assertEquals('Expected to get first match, but subject was not matched', $exception->getMessage());
        $this->assertEquals('my subject', $exception->getSubject());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageParam()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithStringParamConstructor::class, new FirstMatchMessage());
        $subject = new SubjectableImpl('my subject');

        // when
        $exception = $factory->create($subject);

        // then
        $this->assertInstanceOf(ClassWithStringParamConstructor::class, $exception);
        $this->assertEquals('Expected to get first match, but subject was not matched', $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithDefaultConstructor::class, new FirstMatchMessage());
        $subject = new SubjectableImpl('my subject');

        // when
        $exception = $factory->create($subject);

        // then
        $this->assertInstanceOf(ClassWithDefaultConstructor::class, $exception);
    }

    /**
     * @test
     */
    public function shouldInstantiate_withSubjectable()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithSubjectableConstructor::class, new FirstMatchMessage());
        $subject = new SubjectableImpl('my subject');

        // when
        $exception = $factory->create($subject);

        // then
        $this->assertInstanceOf(ClassWithSubjectableConstructor::class, $exception);
    }

    /**
     * @test
     * @dataProvider exceptions
     * @param string $className
     */
    public function shouldInstantiate_withMessage(string $className)
    {
        // given
        $factory = new SignatureExceptionFactory($className, new FirstMatchMessage());
        $subject = new SubjectableImpl('my subject');

        // when
        $exception = $factory->create($subject);

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertEquals('Expected to get first match, but subject was not matched', $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldRethrow_errorWhileInstantiating()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithErrorInConstructor::class, new FirstMatchMessage());
        $subject = new SubjectableImpl('my subject');

        // then
        $this->expectException(Error::class);

        // when
        $factory->create($subject);
    }

    public function exceptions()
    {
        return [
            [Exception::class],
            [InvalidArgumentException::class],
            [SubjectNotMatchedException::class],
        ];
    }
}
