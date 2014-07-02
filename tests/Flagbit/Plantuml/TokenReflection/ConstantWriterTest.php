<?php

namespace Flagbit\Test\Plantuml\TokenReflection;

/**
 * @covers \Flagbit\Plantuml\TokenReflection\ConstantWriter
 */
class ConstantWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\ConstantWriter
     */
    protected $constantWriter;

    protected function setUp()
    {
        $this->constantWriter = new \Flagbit\Plantuml\TokenReflection\ConstantWriter();
    }

    protected function getConstanctMock()
    {
        $constantMock = $this->getMockBuilder('\\TokenReflection\\IReflectionConstant')
            ->getMock();

        $constantMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('MY_CONSTANT_NAME'));

        $constantMock->expects($this->atLeastOnce())
            ->method('getValue')
            ->will($this->returnValue('somevalue'));

        return $constantMock;
    }

    public function testWriteElement()
    {
        $this->assertEquals("    +{static}MY_CONSTANT_NAME = \"somevalue\"\n", $this->constantWriter->writeElement($this->getConstanctMock()));
    }

    public function testWriteElements()
    {
        $this->assertEquals(
            "    +{static}MY_CONSTANT_NAME = \"somevalue\"\n    +{static}MY_CONSTANT_NAME = \"somevalue\"\n    +{static}MY_CONSTANT_NAME = \"somevalue\"\n",
            $this->constantWriter->writeElements(array($this->getConstanctMock(), $this->getConstanctMock(), $this->getConstanctMock()))
        );
    }
}
