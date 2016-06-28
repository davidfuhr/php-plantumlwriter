<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

/**
 * @covers \Flagbit\Plantuml\TokenReflection\WriterOptions
 */
class WriterOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutFunctionParameter()
    {
        $writerOption = new \Flagbit\Plantuml\TokenReflection\WriterOptions();
        $this->assertFalse($writerOption->withoutFunctionParameter);
        $writerOption->withoutFunctionParameter = true;
        $this->assertTrue($writerOption->withoutFunctionParameter);
    }
}