<?php
namespace TRegx\CleanRegex\Internal;

use ArgumentCountError;
use Error;
use Throwable;
use TRegx\CleanRegex\Exception\ClassExpectedException;
use TRegx\CleanRegex\Exception\NoSuitableConstructorException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TypeError;
use function class_exists;
use function interface_exists;
use function strpos;

class SignatureExceptionFactory
{
    /** @var string */
    private $className;
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(string $className, NotMatchedMessage $message)
    {
        $this->className = $className;
        $this->message = $message;
    }

    public function create(string $subjectable): Throwable
    {
        return $this->createWithSignatures([$subjectable]);
    }

    public function createWithoutSubject(): Throwable
    {
        return $this->createWithSignatures([]);
    }

    private function createWithSignatures(array $arguments): Throwable
    {
        $this->validateNotInterface();
        $this->validateClassExists();
        $exception = $this->tryCreate($this->getSignatures($arguments));
        if ($exception instanceof Throwable) {
            return $exception;
        }
        throw ClassExpectedException::notThrowable($this->className);
    }

    private function validateNotInterface(): void
    {
        if (interface_exists($this->className)) {
            throw ClassExpectedException::isInterface($this->className);
        }
    }

    private function validateClassExists(): void
    {
        if (!class_exists($this->className)) {
            throw ClassExpectedException::notFound($this->className);
        }
    }

    /**
     * @param array $signatures
     * @return mixed
     * @throws ClassExpectedException
     * @throws NoSuitableConstructorException
     */
    private function tryCreate(array $signatures)
    {
        foreach ($signatures as $signature) {
            try {
                return $signature();
            } catch (ArgumentCountError $error) {
                continue;
            } catch (TypeError $error) {
                continue;
            } catch (Error $error) {
                if ($this->isAbstractClassError($error)) {
                    throw ClassExpectedException::isAbstract($this->className);
                }
                if ($this->isWrongParametersError($error)) {
                    continue;
                }
                throw $error;
            }
        }
        throw new NoSuitableConstructorException($this->className);
    }

    private function getSignatures(array $arguments): array
    {
        return [
            function () use ($arguments) {
                return new $this->className($this->message->getMessage(), ...$arguments);
            },
            function () {
                return new $this->className($this->message->getMessage());
            },
            function () {
                return new $this->className();
            },
        ];
    }

    private function isAbstractClassError(Error $error): bool
    {
        return $error->getMessage() === "Cannot instantiate abstract class $this->className";
    }

    private function isWrongParametersError(Error $error): bool
    {
        return $this->startsWith($error->getMessage(), "Wrong parameters for $this->className");
    }

    private function startsWith(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) === 0;
    }
}
