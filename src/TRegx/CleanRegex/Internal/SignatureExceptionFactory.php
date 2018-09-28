<?php
namespace TRegx\CleanRegex\Internal;

use ArgumentCountError;
use Error;
use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\ClassExpectedException;
use TRegx\CleanRegex\Exception\CleanRegex\NoSuitableConstructorException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;
use TypeError;
use function class_exists;
use function interface_exists;
use function strlen;
use function substr;

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

    public function create(string $subject): Throwable
    {
        $this->validateNotInterface();
        $this->validateClassExists();
        $exception = $this->tryCreate($subject);
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
     * @param string $subject
     * @return mixed
     * @throws NoSuitableConstructorException
     */
    private function tryCreate(string $subject)
    {
        foreach ($this->getSignatures($subject) as $signature) {
            try {
                return $signature();
            } catch (ArgumentCountError $error) {
                continue;
            } catch (TypeError $error) {
                continue;
            } catch (Error $error) {
                if ($this->isWrongParametersError($error)) {
                    continue;
                }
                throw $error;
            }
        }
        throw new NoSuitableConstructorException($this->className);
    }

    private function getSignatures(string $subject): array
    {
        return [
            function () use ($subject) {
                return new $this->className($this->message->getMessage(), $subject);
            },
            function () {
                return new $this->className($this->message->getMessage());
            },
            function () {
                return new $this->className();
            }
        ];
    }

    private function isWrongParametersError(Error $error): bool
    {
        return $this->startsWith($error->getMessage(), "Wrong parameters for $this->className");
    }

    private function startsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
