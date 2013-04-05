<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionClass;

class ClassWriter extends WriterAbstract
{
    /**
     * @var \Flagbit\Plantuml\TokenReflection\ConstantWriter
     */
    private $constantWriter;

    /**
     * @var \Flagbit\Plantuml\TokenReflection\PropertyWriter
     */
    private $propertyWriter;

    /**
     * @var \Flagbit\Plantuml\TokenReflection\MethodWriter
     */
    private $methodWriter;

    public function __construct()
    {
        $this->setIndent('');
    }

    /**
     * @param \Flagbit\Plantuml\TokenReflection\ConstantWriter $constantWriter
     */
    public function setConstantWriter($constantWriter)
    {
        $this->constantWriter = $constantWriter;
    }

    /**
     * @param \Flagbit\Plantuml\TokenReflection\MethodWriter $methodWriter
     */
    public function setMethodWriter($methodWriter)
    {
        $this->methodWriter = $methodWriter;
    }

    /**
     * @param \Flagbit\Plantuml\TokenReflection\PropertyWriter $propertyWriter
     */
    public function setPropertyWriter($propertyWriter)
    {
        $this->propertyWriter = $propertyWriter;
    }

    /**
     * @param \TokenReflection\IReflectionClass $class
     * @return string
     */
    public function writeElement(IReflectionClass $class)
    {
        $classString = $this->formatLine('class ' . $this->formatClassName($class->getName()) . ' {');

        if ($this->constantWriter) {
            $classString .= $this->constantWriter->writeElements($class->getConstantReflections());
        }

        if ($this->propertyWriter) {
            $classString .= $this->propertyWriter->writeElements($class->getProperties());
        }

        if ($this->methodWriter) {
            $classString .= $this->methodWriter->writeElements($class->getMethods());
        }

        $classString .= $this->formatLine('}');

        if ($class->getParentClassName()) {
            $classString .= $this->formatLine('class ' . $this->formatClassName($class->getName()) . ' extends ' . $this->formatClassName($class->getParentClassName()));
        }

        if ($class->getInterfaceNames()) {
            foreach ($class->getInterfaceNames() as $interfaceName) {
                $classString .= $this->formatLine('class ' . $this->formatClassName($class->getName()) . ' implements ' . $this->formatClassName($interfaceName));
            }
        }

        return $classString;
    }
}
