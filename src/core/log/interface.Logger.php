<?php

interface Logger
{

    public static function sql($string);

    public static function debug($string);

    public static function error($string);

    public static function warn($string);
}
?>