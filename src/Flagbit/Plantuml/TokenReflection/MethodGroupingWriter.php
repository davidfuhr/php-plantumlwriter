<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionMethod;
use TokenReflection\IReflectionParameter;

class MethodGroupingWriter extends MethodWriter
{
    /**
     * @param IReflectionMethod[] $methods
     * @return string
     */
    public function writeElements(array $methods)
    {
        $groups = array('other'=>[],'deprecated'=>[],'todo'=>[]);
        foreach ($methods as $method) {
            if($this->isTodo($method)){
                $groups['todo'][] = $method;
            } else if($this->isDeprecated($method)){
                $groups['deprecated'][] = $method;
            } else {
                $groups['other'][] = $method;
            }
        }
        $methodsString = $this->formatLine($this->writeSeparator());
        if (!empty($groups['other'])) {
            $methodsString .= parent::writeElements($groups['other']);
        }
        if (!empty($groups['todo'])) {
            $methodsString .= $this->formatLine($this->writeTodo());
            $methodsString .= parent::writeElements($groups['todo']);
        }
        if (!empty($groups['deprecated'])) {
            $methodsString .= $this->formatLine($this->writeDeprecated());
            $methodsString .= parent::writeElements($groups['deprecated']);
        }
        return $methodsString;
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    private function isDeprecated(IReflectionMethod $method)
    {
        $returnBool = false;
        preg_match('/\*\h+@deprecated/', (string) $method->getDocComment(), $matches);
        if (isset($matches[0])) {
            $returnBool = true;
        }
        return $returnBool;
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    private function isTodo(IReflectionMethod $method)
    {
        $returnBool = false;
        preg_match('/\*\h+@todo/', (string) $method->getDocComment(), $matches);
        if (isset($matches[0])) {
            $returnBool = true;
        }
        return $returnBool;
    }

    private function writeDeprecated()
    {
        return "-- deprecated --";
    }

    private function writeTodo()
    {
        return "-- todo --";
    }

    private function writeSeparator()
    {
        return "==";
    }
}