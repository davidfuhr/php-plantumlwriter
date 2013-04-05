<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionProperty;

class PropertyWriter extends WriterAbstract
{
    /**
     * @param \TokenReflection\IReflectionProperty $property
     * @return string
     */
    public function writeElement(IReflectionProperty $property)
    {
        return $this->formatLine($this->writeVisibility($property) . $property->getName()
            . $this->writeType($property));
    }

    /**
     * @param $properties
     * @return string
     */
    public function writeElements(array $properties)
    {
        // see https://bugs.php.net/bug.php?id=50688
        @usort($properties, function($a, $b) {
            return strnatcasecmp($a->getName(), $b->getName());
        });

        $propertiesString = '';
        foreach ($properties as $property) {
            /** @var $property \TokenReflection\IReflectionProperty */
            $propertiesString .= $this->writeElement($property);
        }
        return $propertiesString;
    }

    /**
     * @param \TokenReflection\IReflectionProperty $property
     * @return string
     */
    public function writeVisibility(IReflectionProperty $property)
    {
        return $property->isPrivate() ? '-' : ($property->isProtected() ? '#' : '+');
    }

    /**
     * @param \TokenReflection\IReflectionProperty $property
     * @return string
     */
    private function writeType(IReflectionProperty $property)
    {
        $type = '';
        preg_match('/\*\h+@var\h+(\w+)/', (string) $property->getDocComment(), $matches);
        if (isset($matches[1])) {
            $type = ': ' . $matches[1];
        }
        return $type;
    }
}
