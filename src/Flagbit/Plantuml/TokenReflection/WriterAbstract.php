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
     * @return mixed
     */
    protected function formatClassName($className)
    {
        return str_replace('\\', '.', $className);
    }
}
