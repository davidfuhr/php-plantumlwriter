<?php

namespace Flagbit\Plantuml\TokenReflection;

use TokenReflection\IReflectionClass;

abstract class WriterAbstract
{
    /**
     * @var string
     */
    private $indent = '    ';

    /**
     * @var string
     */
    private $linebreak = "\n";

    /**
     * @param string $indent
     */
    protected function setIndent($indent = '    ')
    {
        $this->indent = (string) $indent;
    }

    /**
     * @param string $string
     * @return string
     */
    public function formatLine($string)
    {
        return $this->indent . $string . $this->linebreak;
    }

    /**
     * @param \TokenReflection\IReflectionClass $declaringClass The class using the namespace aliases
     * @param string $aliasedClassName The class name used in the declaring class
     * @return string
     */
    protected function expandNamespaceAlias(IReflectionClass $declaringClass, $aliasedClassName)
    {
        $aliasedClassName = trim($aliasedClassName);
        foreach ($declaringClass->getNamespaceAliases() as $namespaceAlias) {
            if (1 === preg_match('/\\\\' . preg_quote($aliasedClassName) . '$/', $namespaceAlias)) {
                $aliasedClassName = $namespaceAlias;
                break;
            }
        }
        return $aliasedClassName;
    }

    /**
     * @param string $className
     * @return string
     */
    protected function formatClassName($className)
    {
        $className = str_replace('\\', '.', trim($className));
        if ('.' === $className[0]) {
            $className = substr($className, 1);
        }
        return $className;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function formatValue($value)
    {
        if (is_null($value)) {
            $value = 'null';
        }
        else if (is_array($value)) {
            $formattedValues = array();
            foreach ($value as $currentValue) {
                $formattedValues[] = $this->formatValue($currentValue);
            }
            $value = '[' .implode(', ', $formattedValues) . ']';
        }
        else if (is_numeric($value)) {
            // nothing to do here
        }
        else if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        else {
            // make sure we receive two backslashes in the output as
            // plantuml needs them escaped as well
            $value = strtr($value, array(
                "\n" => '\\\\n',
                "\r" => '\\\\r',
                "\t" => '\\\\t',
            ));
            $value = '"' . $value . '"';
        }
        return $value;
    }
}
