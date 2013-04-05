<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionMethod;

class MethodWriter extends \Flagbit\Plantuml\TokenReflection\WriterAbstract
{
    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    public function writeElement(IReflectionMethod $method)
    {
        return $this->formatLine($this->writeVisibility($method)
            . ($method->isStatic() ? '{static}' : '') . $method->getName()
            . $this->writeParameters($method) . $this->writeReturnType($method));
    }

    /**
     * @param array $methods
     * @return string
     */
    public function writeElements(array $methods)
    {
        // see https://bugs.php.net/bug.php?id=50688
        @usort($methods, function($a, $b) {
            return strnatcasecmp($a->getName(), $b->getName());
        });

        $methodsString = '';
        foreach ($methods as $method) {
            /** @var $property \TokenReflection\IReflectionMethod */
            $methodsString .= $this->writeElement($method);
        }
        return $methodsString;
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    private function writeVisibility(IReflectionMethod $method)
    {
        return $method->isPrivate() ? '-' : ($method->isProtected() ? '#' : '+');
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    private function writeParameters(IReflectionMethod $method)
    {
        $parameters = array();
        foreach ($method->getParameters() as $parameter) {
            /** @var $parameter \TokenReflection\IReflectionParameter */
            $parameterString = $parameter->getName();
            if ($parameter->getClassName()) {
                $parameterString .= ': ' . $this->formatClassName($parameter->getClassName());
            }
            $parameters[] = $parameterString;
        }
        return '(' . implode(', ' , $parameters) . ')';
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    private function writeReturnType(IReflectionMethod $method)
    {
        $returnType = '';
        preg_match('/\*\h+@return\h+(\w+)/', (string) $method->getDocComment(), $matches);
        if (isset($matches[1])) {
            $returnType = ': ' . $matches[1];
        }
        return $returnType;
    }
}
