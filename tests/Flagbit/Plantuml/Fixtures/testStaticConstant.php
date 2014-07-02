<?php

class OneClass
{
    const STRING_CONSTANT = 'string';
    const INTEGER_CONSTANT = 7654;
    const FLOAT_CONSTANT = 7654.321;

    const LF_CONSTANT = "\n";
    const CR_CONSTANT = "\r";
    const TAB_CONSTANT = "\t";

    static $NOVALUE_STATIC;
    static $STRING_STATIC = 'string';
    static $INTEGER_STATIC = 7654;
    static $FLOAT_STATIC = 7654.321;

    static $LF_STATIC = "\n";
    static $CR_STATIC = "\r";
    static $TAB_STATIC = "\t";

    static public $PUBLIC_STATIC;
    static protected $PROTECTED_STATIC;
    static private $PRIVATE_STATIC;

    static public function staticPublic()
    {
    }

    static protected function staticProtected()
    {
    }

    static private function staticPrivate()
    {
    }
}
