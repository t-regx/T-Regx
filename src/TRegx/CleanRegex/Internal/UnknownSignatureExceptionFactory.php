<?php
namespace TRegx\CleanRegex\Internal;

use ArgumentCountError;
use TRegx\CleanRegex\Exception\CleanRegex\ClassExpectedException;
use TRegx\CleanRegex\Exception\CleanRegex\NoSuitableConstructorException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use Error;
use Throwable;
use TypeError;

class UnknownSignatureExceptionFactory
{
    /** @var string */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function create(string $subject): Throwable
    {
        $this->validateClassExists();
        $exception = $this->tryCreate($subject);
        if ($exception instanceof Throwable) {
            return $exception;
        }
        throw ClassExpectedException::notThrowable($this->className);
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
                return new $this->className(SubjectNotMatchedException::MESSAGE, $subject);
            },
            function () {
                return new $this->className(SubjectNotMatchedException::MESSAGE);
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

    private function startsWith(string $haystack, string $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
