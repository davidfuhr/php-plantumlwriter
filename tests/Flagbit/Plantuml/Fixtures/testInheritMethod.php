<?php

class ParentClass
{
    public function someParentMethod()
    {
    }

    public function methodToOverride()
    {
    }
}

class ExtendingClass extends ParentClass
{
    public function methodToOverride()
    {
    }
}
