<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionClass;

class ClassWriter extends WriterAbstract
{
    /**
     * @var ConstantWriter
     */
    private $constantWriter;

    /**
     * @var PropertyWriter
     */
    private $propertyWriter;

    /**
     * @var MethodWriter
     */
    private $methodWriter;

    /**
     * @var RelationWriter
     */
    private $relationWriter;

    public function __construct()
    {
        $this->setIndent('');
    }

    /**
     * @param ConstantWriter $constantWriter
     */
    public function setConstantWriter(ConstantWriter $constantWriter)
    {
        $this->constantWriter = $constantWriter;
    }

    /**
     * @param MethodWriter $methodWriter
     */
    public function setMethodWriter(MethodWriter $methodWriter)
    {
        $this->methodWriter = $methodWriter;
    }

    /**
     * @param PropertyWriter $propertyWriter
     */
    public function setPropertyWriter(PropertyWriter $propertyWriter)
    {
        $this->propertyWriter = $propertyWriter;
    }

    /**
     * @param RelationWriter $relationWriter
     */
    public function setRelationWriter(RelationWriter $relationWriter)
    {
        $this->relationWriter = $relationWriter;
    }

    /**
     * @param \TokenReflection\IReflectionClass $class
     *
     * @return string
     */
    public function writeElement(IReflectionClass $class)
    {
        $classString = $this->formatLine(
            $this->writeAbstract($class) . $this->writeObjectType($class) . ' ' . $this->formatClassName(
                $class->getName()
            ) . ' {'
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
                $this->writeObjectType($class) . ' ' . $this->formatClassName($class->getName()) . ' extends '
                . $this->formatClassName(
                    $class->getParentClassName()
                )
            );
        }

        if ($interfaceNames = $class->getOwnInterfaceNames()) {
            foreach ($interfaceNames as $interfaceName) {
                $classString .= $this->formatLine(
                    $this->writeObjectType($class) . ' ' . $this->formatClassName($class->getName()) . ' implements '
                    . $this->formatClassName(
                        $interfaceName
                    )
                );
            }
        }

        if ($this->relationWriter) {
            $classString .= $this->relationWriter->writeElement($class);
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
