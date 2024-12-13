<?php

namespace App\CkanClient;

class SolrUtils
{

    private static $search = [
        '\\',
        '+',
        '-',
        '&',
        '|',
        '!',
        '(',
        ')',
        '{',
        '}',
        '[',
        ']',
        '^',
        '"',
        '~',
        '*',
        '?',
        ':',
        '/',
    ];

    private static $replace = [
        '\\\\',
        '\+',
        '\-',
        '\&',
        '\|',
        '\!',
        '\(',
        '\)',
        '\{',
        '\}',
        '\[',
        '\]',
        '\^',
        '\"',
        '\~',
        '\*',
        '\?',
        '\:',
        '\/',
    ];

    public static function escape($string)
    {
        return str_replace(static::$search, static::$replace, $string);
    }
}