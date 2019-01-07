<?php

class SLN_Enum_DateFormat
{
    const _DEFAULT = 'default';
    const _SHORT   = 'short';
    const _SHORT_COMMA = 'short_comma';
    const _MDY = 'mm/dd/yyyy';
    const _MYSQL = 'mysql';

    private static $labels = array();
    private static $phpFormats = array(
        self::_DEFAULT => 'd M Y',
        self::_SHORT => 'd/m/Y',
        self::_SHORT_COMMA => 'd-m-Y',
        self::_MDY => 'm/d/Y',
        self::_MYSQL => 'Y-m-d',
    );
    private static $jsFormats = array(
        self::_DEFAULT => 'dd M yyyy',
        self::_SHORT => 'dd/mm/yyyy',
        self::_SHORT_COMMA => 'dd-mm-yyyy',
        self::_MDY => 'mm/dd/yyyy',
        self::_MYSQL => 'yyyy-mm-dd',
    );

    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::_DEFAULT];
    }
    public static function getPhpFormat($key)
    {
        return isset(self::$phpFormats[$key]) ? self::$phpFormats[$key] : self::$phpFormats[self::_DEFAULT];
    }
    public static function getJsFormat($key)
    {
        return isset(self::$jsFormats[$key]) ? self::$jsFormats[$key] : self::$jsFormats[self::_DEFAULT];
    }

    public static function init()
    {
        $d = new DateTime;
        $d = $d->format('U');
        foreach(self::$phpFormats as $k => $v){
            self::$labels[$k] = date_i18n($v,$d); 
        }
    }
}

SLN_Enum_DateFormat::init();
