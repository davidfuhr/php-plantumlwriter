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
            . $this->writeType($property) . $this->writeValue($property));
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
    public function writeType(IReflectionProperty $property)
    {
        $type = '';
        preg_match('/\*\h+@var\h+([^\h]+)/', (string) $property->getDocComment(), $matches);
        if (isset($matches[1])) {
            $type = ': ' . $this->formatClassName($matches[1]);
        }
        return $type;
    }

    /**
     * @param IReflectionProperty $property
     * @return string
     */
    public function writeValue(IReflectionProperty $property)
    {
        $value = '';
        if ($property->getDeclaringClass() && $defaultProperties = $property->getDeclaringClass()->getDefaultProperties()) {
            if (!is_null($defaultProperties[$property->getName()])) {
               $value = ' = ' . $this->formatValue($defaultProperties[$property->getName()]);
            }
        }
        return $value;
    }
}
