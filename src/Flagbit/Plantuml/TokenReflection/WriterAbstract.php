<?php

namespace Flagbit\Plantuml\TokenReflection;

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
