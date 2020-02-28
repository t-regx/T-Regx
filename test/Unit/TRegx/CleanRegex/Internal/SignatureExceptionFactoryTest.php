<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use Error;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\Utils\AbstractClass;
use Test\Utils\ClassWithErrorInConstructor;
use Test\Utils\ClassWithoutSuitableConstructor;
use Throwable;
use TRegx\CleanRegex\Exception\ClassExpectedException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\NoSuitableConstructorException;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subject;

class SignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider createMethods
     * @param string $create
     */
    public function shouldThrow_onNotExistingClass(string $create)
    {
        // given
        $factory = new SignatureExceptionFactory('Namespace\NoSuchClass', new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'Namespace\NoSuchClass' does not exists");

        // when
        $factory->$create('');
    }

    /**
     * @test
     * @dataProvider createMethods
     * @param string $create
     */
    public function shouldThrow_onInterface(string $create)
    {
        // given
        $factory = new SignatureExceptionFactory(Throwable::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("'Throwable' is not a class, but an interface");

        // when
        $factory->$create('');
    }

    /**
     * @test
     * @dataProvider createMethods
     * @param string $create
     */
    public function shouldThrow_onAbstractClass(string $create)
    {
        // given
        $factory = new SignatureExceptionFactory(AbstractClass::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("'Test\Utils\AbstractClass' is an abstract class");

        // when
        $factory->$create('');
    }

    /**
     * @test
     * @dataProvider createMethods
     * @param string $create
     */
    public function shouldThrow_onClassThatIsNotThrowable(string $create)
    {
        // given
        $factory = new SignatureExceptionFactory(stdClass::class, new FirstMatchMessage());

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'stdClass' is not throwable");

        // when
        $factory->$create('');
    }

    /**
     * @test
     * @dataProvider createMethods
     * @param string $create
     */
    public function shouldThrow_onClassWithoutSuitableConstructor(string $create)
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithoutSuitableConstructor::class, new FirstMatchMessage());

        // then
        $this->expectException(NoSuitableConstructorException::Class);
        $this->expectExceptionMessage("Class 'Test\Utils\ClassWithoutSuitableConstructor' doesn't have a constructor with supported signature");

        // when
        $factory->$create('');
    }

    /**
     * @test
     * @dataProvider createMethods
     * @param string $create
     */
    public function shouldRethrow_errorWhileInstantiating(string $create)
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithErrorInConstructor::class, new FirstMatchMessage());

        // then
        $this->expectException(Error::class);

        // when
        $factory->$create('my subject');
    }

    public function createMethods(): array
    {
        return [['create'], ['createWithoutSubject'],];
    }
}
