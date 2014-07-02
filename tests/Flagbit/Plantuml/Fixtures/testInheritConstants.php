<?php

class ParentClass
{
    const PARENT_CONSTANT = true;
    const INHERIT_CONSTANT = true;
}

class ExtendingClass extends ParentClass
{
    const PARENT_CONSTANT = false;
}
