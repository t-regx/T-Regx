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

    public function throwable(NotMatchedMessage $message, ?Subject $subject): Throwable
    {
        $instance = $this->instance($message, $subject);
        if ($instance instanceof Throwable) {
            return $instance;
        }
        throw ClassExpectedException::notThrowable($this->className);
    }

    private function instance(NotMatchedMessage $message, ?Subject $subject)
    {
        if (\class_exists($this->className)) {
            return $this->classInstance($message, $subject);
        }
        throw $this->notClassException();
    }

    private function classInstance(NotMatchedMessage $message, ?Subject $subject)
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

    private function signatures(NotMatchedMessage $message, ?Subject $subject): array
    {
        return [
            function () use ($message, $subject) {
                if ($subject === null) {
                    return new $this->className($message->getMessage());
                }
                return new $this->className($message->getMessage(), $subject->getSubject());
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
        $message = new Chars($error->getMessage());
        return $message->startsWith("Wrong parameters for $className");
    }

    private function notClassException(): ClassExpectedException
    {
        if (\interface_exists($this->className)) {
            return ClassExpectedException::isInterface($this->className);
        }
        return ClassExpectedException::notFound($this->className);
    }
}
