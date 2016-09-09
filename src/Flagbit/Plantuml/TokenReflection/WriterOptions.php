<?php

namespace Flagbit\Plantuml\TokenReflection;

class WriterOptions
{
    private $withoutFunctionParameter = false;
    private $maxLineLength = null;

    public function __set($optionName, $value)
    {
        switch($optionName)
        {
            case 'withoutFunctionParameter':
                $this->withoutFunctionParameter = (bool) $value;
                break;
            case 'maxLineLength':
                $this->maxLineLength = (int) $value;
                break;
        }
    }

    public function __get($optionName)
    {
        switch($optionName)
        {
            case 'withoutFunctionParameter':
                return $this->withoutFunctionParameter;
            case 'maxLineLength':
                return $this->maxLineLength;
        }
    }
}
