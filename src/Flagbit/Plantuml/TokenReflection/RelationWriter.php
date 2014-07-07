<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionClass;

class RelationWriter extends WriterAbstract
{
    public function writeElement(IReflectionClass $class)
    {
        $relations = '';

        $properties = $class->getOwnProperties();
        foreach ($properties as $property) {
            /** @var $property \TokenReflection\IReflectionProperty */

            $type = null;
            preg_match('/\*\h+@var\h+([^\h]+)/', (string) $property->getDocComment(), $matches);
            if (isset($matches[1])) {
                $type = $matches[1];
                if ($property->getDeclaringClass()) {
                    $type = $this->expandNamespaceAlias($property->getDeclaringClass(), $type);
                }
            }

            if (in_array($type, array(null, 'string', 'int', 'integer', 'float', 'bool', 'boolean', 'string', 'double', 'array'))) {
                continue;
            }

            // TODO Prefix with namespace only if class is not from root namespace
            $type = $this->formatClassName($class->getNamespaceName() . '.' . $type);

            $relations .= $this->formatClassName($class->getName()) . ' "?" --> "1" ' . $type . ': "' . $property->getName() . "\"\n";
        }

        return $relations;
    }
}
