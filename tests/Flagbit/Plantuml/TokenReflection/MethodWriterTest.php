<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

/**
 * @covers \Flagbit\Plantuml\TokenReflection\MethodWriter
 */
class MethodWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\MethodWriter
     */
    protected $methodWriter;

    protected $methodParameters = array();

    protected function setUp()
    {
        $this->methodWriter = new \Flagbit\Plantuml\TokenReflection\MethodWriter();
    }

    protected function getMethodMock()
    {
        $methodMock = $this->getMockBuilder('\\TokenReflection\\IReflectionMethod')
            ->getMock();

        $methodMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('myMethodName'));

        $methodMock->expects($this->atLeastOnce())
            ->method('getParameters')
            ->will($this->returnValue($this->methodParameters));

        return $methodMock;
    }

    public function testWritePrivateMethod()
    {
        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->any())
            ->method('isPrivate')
            ->will($this->returnValue(true));
        $methodMock->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(false));
        $methodMock->expects($this->any())
            ->method('isPublic')
            ->will($this->returnValue(false));

        $this->assertEquals("    -myMethodName()\n", $this->methodWriter->writeElement($methodMock));
    }

    public function testWriteProtectedMethod()
    {
        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->any())
            ->method('isPrivate')
            ->will($this->returnValue(false));
        $methodMock->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(true));
        $methodMock->expects($this->any())
            ->method('isPublic')
            ->will($this->returnValue(false));

        $this->assertEquals("    #myMethodName()\n", $this->methodWriter->writeElement($methodMock));
    }

    public function testWritePublicMethod()
    {
        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->any())
            ->method('isPrivate')
            ->will($this->returnValue(false));
        $methodMock->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(false));
        $methodMock->expects($this->any())
            ->method('isPublic')
            ->will($this->returnValue(true));

        $this->assertEquals("    +myMethodName()\n", $this->methodWriter->writeElement($methodMock));
    }

    public function testWriteParameter()
    {
        $parameterMock = $this->getMockBuilder('\\TokenReflection\\IReflectionParameter')
            ->getMock();
        $parameterMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('someParameter'));

        $this->methodParameters = array($parameterMock);

        $methodMock = $this->getMethodMock();

        $this->assertContains('(someParameter)', $this->methodWriter->writeElement($methodMock));
    }

    public function testWriteParameterTypeFromDocComment()
    {
        $parameterMock = $this->getMockBuilder('\\TokenReflection\\IReflectionParameter')
            ->getMock();
        $parameterMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('someParameter'));

        $this->methodParameters = array($parameterMock);

        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @param string $someParameter
 */'));

        $this->assertContains('(someParameter : string)', $this->methodWriter->writeElement($methodMock));
    }

    public function testWriteParameterDefaultValue()
    {
        $parameterMock = $this->getMockBuilder('\\TokenReflection\\IReflectionParameter')
            ->getMock();
        $parameterMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('someParameter'));
        $parameterMock->expects($this->any())
            ->method('isDefaultValueAvailable')
            ->will($this->returnValue(true));
        $parameterMock->expects($this->any())
            ->method('isOptional')
            ->will($this->returnValue(true));

        $this->methodParameters = array($parameterMock);
        $methodMock = $this->getMethodMock();

        $this->assertContains('(someParameter = null)', $this->methodWriter->writeElement($methodMock));
    }

    public function testWriteReturnValue()
    {
        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @return string
 */'));

        $this->assertStringEndsWith(" : string\n", $this->methodWriter->writeElement($methodMock));
    }

    public function testWriteNamespacedClassReturnValue()
    {
        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @return \\Flagbit\\TestClass
 */'));

        $this->assertStringEndsWith(" : Flagbit.TestClass\n", $this->methodWriter->writeElement($methodMock));
    }
}
