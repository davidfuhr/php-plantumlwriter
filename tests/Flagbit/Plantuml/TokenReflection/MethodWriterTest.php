<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

class MethodWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\MethodWriter
     */
    protected $methodWriter;

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
            ->will($this->returnValue(array()));

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
}
