<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

/**
 * @covers \Flagbit\Plantuml\TokenReflection\MethodWriter
 */
class MethodGroupingWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\MethodWriter
     */
    protected $methodWriter;

    protected $methodParameters = array();

    protected function setUp()
    {
        $this->methodWriter = new \Flagbit\Plantuml\TokenReflection\MethodGroupingWriter();
    }

    protected function getMethodMock($methodName = null, $methodParameters = null)
    {
        $methodMock = $this->getMockBuilder('\\TokenReflection\\IReflectionMethod')
            ->getMock();

        $methodMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue(empty($methodName)?'myMethodName':$methodName));

        $methodMock->expects($this->atLeastOnce())
            ->method('getParameters')
            ->will($this->returnValue(empty($methodParameters)?$this->methodParameters:$methodParameters));

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

    public function testWriteGroupDeprecated()
    {
        $methodMock = $this->getMethodMock();
        $methodMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
* @deprecated 1.1 Use oneMethod instead
* @return bool
*/'));
        $output = $this->methodWriter->writeElements(array($methodMock));
        $this->assertContains("-- deprecated --\n", $output);
        $this->assertContains(" : bool\n", $output);
    }

    public function testWriteGroupDeprecatedMultipleMethods()
    {
        $methodMock1 = $this->getMethodMock();
        $methodMock1->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @deprecated 1.1 Use oneMethod instead
 * @return bool
 */'));
        $methodMock2 = $this->getMethodMock('myMethodName2');
        $methodMock2->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @deprecated
 * @return int
 */'));
        $output = $this->methodWriter->writeElements(array($methodMock1,$methodMock2));
        $this->assertEquals("    ==\n    -- deprecated --\n    +myMethodName() : bool\n    +myMethodName2() : int\n", $output);
    }

    public function testWriteGroupTodoMultipleMethods()
    {
        $methodMock1 = $this->getMethodMock('aMethod');
        $methodMock1->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @todo Use oneMethod instead
 * @return bool
 */'));
        $parameterMock = $this->getMockBuilder('\\TokenReflection\\IReflectionParameter')
            ->getMock();
        $parameterMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('someParameter'));

        $methodMock2 = $this->getMethodMock('anotherMethod',array($parameterMock));
        $methodMock2->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @todo
 * @param string $someParameter
 * @return int
 */'));
        $output = $this->methodWriter->writeElements(array($methodMock1,$methodMock2));
        $this->assertEquals("    ==\n    -- todo --\n    +aMethod() : bool\n    +anotherMethod(someParameter : string) : int\n", $output);
    }
}
