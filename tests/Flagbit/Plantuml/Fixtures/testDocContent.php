<?php

/**
 * Fixture to Test DocContent puml file generation.
 *
 * @author Tom Niessink <tom.niessink@icloud.com>
 *
 * @property-read int $docContentReadProperty
 * @property-write float $docContentWriteProperty
 * @property double $docContentProperty
 * @property string $collidingProperty
 * @method int DocContentMethod()
 * @method float DocContentMethodWithArguments(arg1 : int, arg2 : string)
 * @method double DocContentMethodWithOtherArguments(int $arg1, string $arg2)
 * @method string CollidingMethodName()
 */
class DocContent
{
    /** @var string */
    private $normalProperty;

    /** @var int */
    public $collidingProperty;

    /**
     * @return double
     */
    protected function NormalMethod() {

    }

    /**
     * @return int
     */
    public static function CollidingMethodName() {

    }
}
