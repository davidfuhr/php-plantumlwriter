<?php

namespace Flagbit\Plantuml\TokenReflection;

class WriterOptions
{
    private $withoutFunctionParameter = false;

    public function __set($optionName, $value)
    {
        switch($optionName)
        {
            case 'withoutFunctionParameter':
                $this->withoutFunctionParameter = (bool) $value;
                break;
        }
    }

    public function __get($optionName)
    {
        switch($optionName)
        {
            case 'withoutFunctionParameter':
                return $this->withoutFunctionParameter;
        }
    }
}