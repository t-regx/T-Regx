<?php
namespace TRegx\CleanRegex\Internal;

use ArgumentCountError;
use Error;
use Throwable;
use TRegx\CleanRegex\Exception\ClassExpectedException;
use TRegx\CleanRegex\Exception\NoSuitableConstructorException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TypeError;

class SignatureExceptionFactory
{
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(NotMatchedMessage $message)
    {
        $this->message = $message;
    }

    public function create(string $className, string $subjectable): Throwable
    {
        return $this->createWithSignatures($className, [$subjectable]);
    }

    public function createWithoutSubject(string $className): Throwable
    {
        return $this->createWithSignatures($className, []);
    }

    private function createWithSignatures(string $className, array $arguments): Throwable
    {
        $this->validateNotInterface($className);
        $this->validateClassExists($className);
        $exception = $this->tryCreate($className, $this->getSignatures($className, $arguments));
        if ($exception instanceof Throwable) {
            return $exception;
        }
        throw ClassExpectedException::notThrowable($className);
    }

    private function validateNotInterface(string $className): void
    {
        if (\interface_exists($className)) {
            throw ClassExpectedException::isInterface($className);
        }
    }

    private function validateClassExists(string $className): void
    {
        if (!\class_exists($className)) {
            throw ClassExpectedException::notFound($className);
        }
    }

    private function tryCreate(string $className, array $signatures)
    {
        foreach ($signatures as $signature) {
            try {
                return $signature();
            } catch (ArgumentCountError | TypeError $error) {
                continue;
            } catch (Error $error) {
                if ($this->isAbstractClassError($className, $error)) {
                    throw ClassExpectedException::isAbstract($className);
                }
                if ($this->isWrongParametersError($className, $error)) {
                    continue;
                }
                throw $error;
            }
        }
        throw new NoSuitableConstructorException($className);
    }

    private function getSignatures(string $className, array $arguments): array
    {
        return [
            function () use ($className, $arguments) {
                return new $className($this->message->getMessage(), ...$arguments);
            },
            function () use ($className) {
                return new $className($this->message->getMessage());
            },
            function () use ($className) {
                return new $className();
            },
        ];
    }

    private function isAbstractClassError(string $className, Error $error): bool
    {
        return $error->getMessage() === "Cannot instantiate abstract class $className";
    }

    private function isWrongParametersError(string $className, Error $error): bool
    {
        return $this->startsWith($error->getMessage(), "Wrong parameters for $className");
    }

    private function startsWith(string $haystack, string $needle): bool
    {
        return \strpos($haystack, $needle) === 0;
    }
}
