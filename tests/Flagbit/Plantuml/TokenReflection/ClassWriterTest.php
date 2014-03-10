<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

/**
 * @covers \Flagbit\Plantuml\TokenReflection\ClassWriter
 */
class ClassWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\ClassWriter
     */
    protected $classWriter;

    protected function setUp()
    {
        $this->classWriter = new \Flagbit\Plantuml\TokenReflection\ClassWriter();
    }

    protected function getClassMock()
    {
        $classMock = $this->getMockBuilder('\\TokenReflection\\IReflectionClass')
            ->getMock();

        $classMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('MyClassName'));

        return $classMock;
    }

    public function testWriteClassName()
    {
        $this->assertEquals("class MyClassName {\n}\n", $this->classWriter->writeElement($this->getClassMock()));
    }

    public function testWriteAbstractClassName()
    {
        $classMock = $this->getClassMock();

        $classMock->expects($this->atLeastOnce())
            ->method('isAbstract')
            ->will($this->returnValue(true));

        $classMock->expects($this->atLeastOnce())
            ->method('isInterface')
            ->will($this->returnValue(false));

        $this->assertEquals("abstract class MyClassName {\n}\n", $this->classWriter->writeElement($classMock));
    }

    public function testWriteInterfaceClassName()
    {
        $classMock = $this->getClassMock();

        $classMock->expects($this->atLeastOnce())
            ->method('isInterface')
            ->will($this->returnValue(true));

        $this->assertEquals("interface MyClassName {\n}\n", $this->classWriter->writeElement($classMock));
    }

    public function testWriteInterfaceNotAbstractClassName()
    {
        $classMock = $this->getClassMock();

        $classMock->expects($this->atLeastOnce())
            ->method('isAbstract')
            ->will($this->returnValue(true));

        $classMock->expects($this->atLeastOnce())
            ->method('isInterface')
            ->will($this->returnValue(true));

        $this->assertEquals("interface MyClassName {\n}\n", $this->classWriter->writeElement($classMock));
    }
}
