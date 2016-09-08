<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionProperty;

class PropertyGroupingWriter extends PropertyWriter
{
    /**
     * @param IReflectionProperty[] $properties
     * @return string
     */
    public function writeElements(array $properties)
    {
        $groups = array('other'=>array(),'deprecated'=>array(),'todo'=>array());
        foreach ($properties as $property) {
            if($this->isTodo($property)){
                $groups['todo'][] = $property;
            } else if($this->isDeprecated($property)){
                $groups['deprecated'][] = $property;
            } else {
                $groups['other'][] = $property;
            }
        }
        $propertiesString = $this->formatLine($this->writeSeparator());;
        if (!empty($groups['other'])) {
            $propertiesString .= parent::writeElements($groups['other']);
        }
        if (!empty($groups['todo'])) {
            $propertiesString .= $this->formatLine($this->writeTodo());
            $propertiesString .= parent::writeElements($groups['todo']);
        }
        if (!empty($groups['deprecated'])) {
            $propertiesString .= $this->formatLine($this->writeDeprecated());
            $propertiesString .= parent::writeElements($groups['deprecated']);
        }
        return $propertiesString;
    }

    /**
     * @param \TokenReflection\IReflectionProperty $property
     * @return string
     */
    private function isDeprecated(IReflectionProperty $property)
    {
        $returnBool = false;
        preg_match('/\*\h+@deprecated/', (string) $property->getDocComment(), $matches);
        if (isset($matches[0])) {
            $returnBool = true;
        }
        return $returnBool;
    }

    /**
     * @param \TokenReflection\IReflectionProperty $property
     * @return string
     */
    private function isTodo(IReflectionProperty $property)
    {
        $returnBool = false;
        preg_match('/\*\h+@todo/', (string) $property->getDocComment(), $matches);
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
