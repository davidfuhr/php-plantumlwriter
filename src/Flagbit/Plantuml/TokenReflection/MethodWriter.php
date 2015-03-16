<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionMethod;
use TokenReflection\IReflectionParameter;

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
     * @param IReflectionMethod[] $methods
     * @return string
     */
    public function writeElements(array $methods)
    {
        // see https://bugs.php.net/bug.php?id=50688
        @usort($methods, function(IReflectionMethod $a, IReflectionMethod $b) {
            return strnatcasecmp($a->getName(), $b->getName());
        });

        $methodsString = '';
        foreach ($methods as $method) {
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
            $parameters[] = $this->writeParameter($method, $parameter);
        }
        return '(' . implode(', ' , $parameters) . ')';
    }

    /**
     * @param IReflectionMethod $method
     * @param IReflectionParameter $parameter
     * @return string
     */
    private function writeParameter(IReflectionMethod $method, IReflectionParameter $parameter)
    {
        $parameterString = $parameter->getName();

        if ($parameter->getClassName()) {
            $parameterString .= ' : ' . $this->formatClassName($parameter->getClassName());
        }
        else {
            preg_match('/\*\h+@param\h+([^\h]+)\h+\$' . preg_quote($parameterString). '\s/', (string) $method->getDocComment(), $matches);
            if (isset($matches[1])) {
                $parameterString .= ' : ' . $this->formatClassName($matches[1]);
            }
        }

        if ($parameter->isOptional() && $parameter->isDefaultValueAvailable()) {
            $parameterString .= ' = ' . $this->formatValue($parameter->getDefaultValue());
        }

        return $parameterString;
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    private function writeReturnType(IReflectionMethod $method)
    {
        $returnType = '';
        preg_match('/\*\h+@return\h+([^\h]+)/', (string) $method->getDocComment(), $matches);
        if (isset($matches[1])) {
            $returnType = $matches[1];
            if ($method->getDeclaringClass()) {
                $returnType = $this->expandNamespaceAlias($method->getDeclaringClass(), $returnType);
            }
            $returnType = ' : ' . $this->formatClassName($returnType);
        }
        return $returnType;
    }
}
