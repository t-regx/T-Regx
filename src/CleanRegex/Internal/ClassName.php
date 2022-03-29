<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Exception\ClassExpectedException;
use TRegx\CleanRegex\Exception\NoSuitableConstructorException;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class ClassName
{
    /** @var string */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function throwable(NotMatchedMessage $message, Subject $subject): Throwable
    {
        $instance = $this->instance($message, $subject);
        if ($instance instanceof Throwable) {
            return $instance;
        }
        throw ClassExpectedException::notThrowable($this->className);
    }

    private function instance(NotMatchedMessage $message, Subject $subject)
    {
        if (\class_exists($this->className)) {
            return $this->classInstance($message, $subject);
        }
        throw $this->notClassException();
    }

    private function classInstance(NotMatchedMessage $message, Subject $subject)
    {
        foreach ($this->signatures($message, $subject) as $signature) {
            try {
                return $signature();
            } catch (\ArgumentCountError | \TypeError $error) {
                continue;
            } catch (\Error $error) {
                if ($this->isAbstractClassError($this->className, $error)) {
                    throw ClassExpectedException::isAbstract($this->className);
                }
                if ($this->isWrongParametersError($this->className, $error)) {
                    continue;
                }
                throw $error;
            }
        }
        throw new NoSuitableConstructorException($this->className);
    }

    private function signatures(NotMatchedMessage $message, Subject $subject): array
    {
        return [
            function () use ($message, $subject) {
                return new $this->className($message->getMessage(), $subject->asString());
            },
            function () use ($message) {
                return new $this->className($message->getMessage());
            },
            function () use ($message) {
                return new $this->className();
            },
        ];
    }

    private function isAbstractClassError(string $className, \Error $error): bool
    {
        return $error->getMessage() === "Cannot instantiate abstract class $className";
    }

    private function isWrongParametersError(string $className, \Error $error): bool
    {
        return \strPos($error->getMessage(), "Wrong parameters for $className") === 0;
    }

    private function notClassException(): ClassExpectedException
    {
        if (\interface_exists($this->className)) {
            return ClassExpectedException::isInterface($this->className);
        }
        return ClassExpectedException::notFound($this->className);
    }
}
