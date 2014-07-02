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
     *
     * @return string
     */
    public function writeElement(IReflectionClass $class)
    {
        $classString = $this->formatLine(
            sprintf('%s "%s"',
            $this->writeAbstract($class) . $this->writeObjectType($class),
            $this->formatClassName(
                $class->getName()
            )) . ' {'
        );

        if ($this->constantWriter) {
            $constantReflections = $class->getOwnConstantReflections();
            foreach ($class->getConstantReflections() as $otherConstantReflection) {
                /* @var $otherConstantReflection \TokenReflection\ReflectionConstant */
                $otherConstantName = $otherConstantReflection->getName();

                foreach ($constantReflections as $constantReflection) {
                    if ($constantReflection->getName() === $otherConstantName) {
                        // skip constants already defined in our current class
                        continue 2;
                    }
                }

                $constantReflections[] = $otherConstantReflection;
            }

            $classString .= $this->constantWriter->writeElements($constantReflections);
        }

        if ($this->propertyWriter) {
            $classString .= $this->propertyWriter->writeElements($class->getOwnProperties());
        }

        if ($this->methodWriter) {
            $classString .= $this->methodWriter->writeElements($class->getOwnMethods());
        }

        $classString .= $this->formatLine('}');

        if ($class->getParentClassName()) {
            $classString .= $this->formatLine(
                sprintf('"%s" <|-- "%s"',
                $this->formatClassName(
                    $class->getParentClassName()
                ),
                $this->formatClassName($class->getName())
            ));
        }

        if ($interfaceNames = $class->getOwnInterfaceNames()) {
            foreach ($interfaceNames as $interfaceName) {
                $classString .= $this->formatLine(
                    sprintf('"%s" <|.. "%s"',
                    $this->formatClassName(
                        $interfaceName
                    ),
                    $this->formatClassName($class->getName())
                ));
            }
        }

        return $classString;
    }

    /**
     * @param IReflectionClass $class
     *
     * @return string
     */
    private function writeAbstract(IReflectionClass $class)
    {
        $return = '';
        if (true === $class->isAbstract() && false === $class->isInterface()) {
            $return = 'abstract ';
        }
        return $return;
    }

    /**
     * @param IReflectionClass $class
     *
     * @return string
     */
    private function writeObjectType(IReflectionClass $class)
    {
        $return = 'class';
        if (true === $class->isInterface()) {
            $return = 'interface';
        }
        return $return;
    }
}
