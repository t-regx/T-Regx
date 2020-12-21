<?php
namespace Test\Utils;

use PHPUnit\Framework\MockObject\MockBuilder;

trait ThrowsForUnmockedMethods
{
    public function getMockBuilder($className): MockBuilder
    {
        return parent::getMockBuilder($className)->disableAutoReturnValueGeneration();
    }
}
