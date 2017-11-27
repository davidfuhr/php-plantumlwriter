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
     * @param integer $times How much indentation needed to be repeated
     * @param bool $addingTabs Whether appending tab character after each indentation
     * @return string
     */
    public function indenting($times = 1, $addingTabs = false)
    {
        $tabChar = $addingTabs ? '\t' : '';

        $indent = '';
        for ($i=0; $i < $times; $i++) {
            $tab = $i==0 ? '' : $tabChar;
            $indent .= $this->indent . $tab;
        }
        return $indent;
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
    protected function formatValue($value, $_arrayDepth = 0)
    {
        if (is_null($value)) {
            $value = 'null';
        }
        else if (is_array($value)) {
            $formattedValues = array();
            $_arrayDepth++;
            foreach ($value as $key => $currentValue) {
                // recursively formatting array values
                $formattedValues[] = $this->formatValue($key) . ' => ' . $this->formatValue($currentValue, $_arrayDepth);
            }
            $value = count($formattedValues) > 0
                ? "[\n{$this->indenting($_arrayDepth+1, true)}" .implode(",\n{$this->indenting($_arrayDepth+1, true)}", $formattedValues) . "\n{$this->indenting($_arrayDepth, true)}]"
                : '[' .implode(', ', $formattedValues) . ']';
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
                "\l" => '\\\\l',
            ));
            $value = '"' . $value . '"';
        }
        return $value;
    }
}
