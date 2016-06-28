<?php

class OneClass
{
    /**
     * @return bool
     */
    function zeroMethod()
    {
    }

    public function objectTypeHint(OneClass $oneClass)
    {
    }

    /**
     * @param OneClass $oneClass
     *
     * @return OneClass
     */
    public function objectTypeHintAndDocComment(OneClass $oneClass)
    {
    }

    /**
     * @param int   $int
     * @param float $float
     * @param bool  $bool
     */
    protected function scalarParameters($int, $float, $bool)
    {
    }

    /**
     * @param WrongDocComment $oneClass
     */
    private function typeHintWinsOverDocComment(OneClass $oneClass)
    {
    }

    public function parametersWithValues($int = 1, $float = 12.1, $string = 'foo', $bool = true)
    {
    }
}
