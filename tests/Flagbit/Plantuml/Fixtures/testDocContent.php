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
 * @method int docContentMethod()
 * @method float docContentMethodWithArguments(arg1 : int, arg2 : string)
 * @method double docContentMethodWithOtherArguments(int $arg1, string $arg2)
 * @method string collidingMethodName()
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
    protected function normalMethod() {

    }

    /**
     * @return int
     */
    public static function collidingMethodName() {

    }
}
