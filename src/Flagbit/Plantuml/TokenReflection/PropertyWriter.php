<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionProperty;

class PropertyWriter extends WriterAbstract
{
    /**
     * @param IReflectionProperty $property
     *
     * @return string
     */
    public function writeElement(IReflectionProperty $property)
    {
        return $this->formatLine($this->writeVisibility($property) . $property->getName()
            . $this->writeType($property) . $this->writeValue($property));
    }

    /**
     * @param IReflectionProperty[] $properties
     *
     * @return string
     */
    public function writeElements(array $properties)
    {
        // see https://bugs.php.net/bug.php?id=50688
        @usort($properties, function(IReflectionProperty $a, IReflectionProperty $b) {
            return strnatcasecmp($a->getName(), $b->getName());
        });

        $propertiesString = '';
        foreach ($properties as $property) {
            /** @var $property IReflectionProperty */
            $propertiesString .= $this->writeElement($property);
        }
        return $propertiesString;
    }

    /**
     * @param IReflectionProperty $property
     *
     * @return string
     */
    public function writeVisibility(IReflectionProperty $property)
    {
        return $property->isPrivate() ? '-' : ($property->isProtected() ? '#' : '+');
    }

    /**
     * @param IReflectionProperty $property
     *
     * @return string
     */
    public function writeType(IReflectionProperty $property)
    {
        $type = '';
        preg_match('/\*\h+@var\h+([^\h]+)/', (string) $property->getDocComment(), $matches);
        if (isset($matches[1])) {
            $type = $matches[1];
            if ($property->getDeclaringClass()) {
                $type = $this->expandNamespaceAlias($property->getDeclaringClass(), $type);
            }
            $type = ' : ' . $this->formatClassName($type);
        }
        return $type;
    }

    /**
     * @param IReflectionProperty $property
     *
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
