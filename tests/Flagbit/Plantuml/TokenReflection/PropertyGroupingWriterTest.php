<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

/**
 * @covers \Flagbit\Plantuml\TokenReflection\PropertyWriter
 */
class PropertyGroupingWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\PropertyWriter
     */
    protected $propertyWriter;

    protected function setUp()
    {
        $this->propertyWriter = new \Flagbit\Plantuml\TokenReflection\PropertyGroupingWriter();
    }

    protected function getPropertyMock()
    {
        $propertyMock = $this->getMockBuilder('\\TokenReflection\\IReflectionProperty')
            ->getMock();

        $propertyMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('myPropertyName'));

        return $propertyMock;
    }

    public function testWritePrivateProperty()
    {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->any())
            ->method('isPrivate')
            ->will($this->returnValue(true));
        $propertyMock->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(false));
        $propertyMock->expects($this->any())
            ->method('isPublic')
            ->will($this->returnValue(false));

        $this->assertEquals("    -myPropertyName\n", $this->propertyWriter->writeElement($propertyMock));
    }

    public function testWriteProtectedProperty()
    {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->any())
            ->method('isPrivate')
            ->will($this->returnValue(false));
        $propertyMock->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(true));
        $propertyMock->expects($this->any())
            ->method('isPublic')
            ->will($this->returnValue(false));

        $this->assertEquals("    #myPropertyName\n", $this->propertyWriter->writeElement($propertyMock));
    }

    public function testWritePublicProperty()
    {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->any())
            ->method('isPrivate')
            ->will($this->returnValue(false));
        $propertyMock->expects($this->any())
            ->method('isProtected')
            ->will($this->returnValue(false));
        $propertyMock->expects($this->any())
            ->method('isPublic')
            ->will($this->returnValue(true));

        $this->assertEquals("    +myPropertyName\n", $this->propertyWriter->writeElement($propertyMock));
    }

    public function testWriteType()
    {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @var string
 */'));

        $this->assertStringEndsWith(": string\n", $this->propertyWriter->writeElement($propertyMock));
    }

    public function testWriteNamespacedClassType()
    {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @var \\Flagbit\\TestClass
 */'));

        $this->assertStringEndsWith(": Flagbit.TestClass\n", $this->propertyWriter->writeElement($propertyMock));
    }

    public function testWriteWithGroupingDeprecated()
    {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @var string
 * @deprecated
 */'));

        $this->assertEquals("    ==\n    -- deprecated --\n    +myPropertyName : string\n", $this->propertyWriter->writeElements(array($propertyMock)));
    }

    public function testWriteWithGroupingTodo() {
        $propertyMock = $this->getPropertyMock();
        $propertyMock->expects($this->atLeastOnce())
            ->method('getDocComment')
            ->will($this->returnValue('/**
 * @var string
 * @todo change this
 */'));

        $this->assertEquals("    ==\n    -- todo --\n    +myPropertyName : string\n", $this->propertyWriter->writeElements(array($propertyMock)));
    }
}
