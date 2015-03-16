<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionClass;
use TokenReflection\IReflectionParameter;

class DocContentWriter extends \Flagbit\Plantuml\TokenReflection\WriterAbstract
{
    /**
     * @param \TokenReflection\IReflectionClass $class
     * @return string
     */
    public function writeProperties(\TokenReflection\IReflectionClass $class)
    {
        $written = '';
        $docComment = (string)$class->getDocComment();
        $matches = array();
        preg_match_all('/\*\h+@property(?:-read|-write|)\h+([^\h]+)\h+\$([^\s]+)\s/', (string)$docComment, $matches);
        foreach($matches[2] as $i => $name) {
            $written .= $this->writeProperty($name, $matches[1][$i]);
        }
        return $written;
    }

    /**
     * @param \TokenReflection\IReflectionMethod $method
     * @return string
     */
    protected function writeProperty($name, $type) {
        return $this->formatLine($this->writeVisibility() . $name
            . $this->writeType($type));
    }


    /**
     * @param string $type
     * @return string
     */
    public function writeType($type)
    {
        return ' : '.$type;
    }

    /**
     * Public by definition if in a docblock.
     * @return string
     */
    protected function writeVisibility() {
        return '+';
    }

    /**
     * @param \TokenReflection\IReflectionClass $class
     * @return string
     */
    public function writeMethods(IReflectionClass $class)
    {
        $written = '';
        $docComment = (string)$class->getDocComment();
        $matches = array();
        preg_match_all('/\*\h+@method\h+([^\h]+)\h+([^(\s]+)(?:\h*\(\h*([^)]*)\h*\))?\s/', (string)$docComment, $matches);
        foreach($matches[2] as $i => $name) {
            $written .= $this->writeMethod($name, $matches[3][$i], $matches[1][$i]);
        }
        return $written;
    }

    /**
     * @param \TokenReflection\IReflectionClass $class
     * @return string
     */
    protected function writeMethod($name, $params, $returnType)
    {
        return $this->formatLine($this->writeVisibility()
            . $name . $this->writeParameters($params)
            . $this->writeReturnType($returnType));
    }

    /**
     * @param string $returnType
     * @return string
     */
    protected function writeReturnType($returnType) {
        return $this->writeType($returnType);
    }


    /**
     * @param string $params
     * @return string
     */
    private function writeParameters($params)
    {
        return '(' . $params . ')';
    }

}
