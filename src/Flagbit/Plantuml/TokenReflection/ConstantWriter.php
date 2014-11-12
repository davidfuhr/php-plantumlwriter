<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionConstant;

class ConstantWriter extends WriterAbstract
{
    /**
     * @param IReflectionConstant $constant
     *
     * @return string
     */
    public function writeElement(IReflectionConstant $constant)
    {
        return $this->formatLine('+{static}' . $constant->getName() . ' = ' . $this->formatValue($constant->getValue()));
    }

    /**
     * @param IReflectionConstant[] $constants
     *
     * @return string
     */
    public function writeElements(array $constants)
    {
        // see https://bugs.php.net/bug.php?id=50688
        @usort($constants, function(IReflectionConstant $a, IReflectionConstant $b) {
            return strnatcasecmp($a->getName(), $b->getName());
        });

        $constantsString = '';
        foreach ($constants as $constant) {
            /** @var $property IReflectionConstant */
            $constantsString .= $this->writeElement($constant);
        }
        return $constantsString;
    }
}
