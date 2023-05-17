<?php

namespace App\Helpers;

class DataFormat
{
    const FORMAT_STRING = 'string';
    const FORMAT_JSON = 'json';
    const FORMAT_BOOL = 'bool';
    const FORMAT_INT = 'int';
    const FORMAT_DOUBLE = 'double';
    const FORMAT_ARRAY = 'array';

    const VALUE_TRUE = 'true';
    const VALUE_FALSE = 'false';

    /**
     * Default format
     *
     * @return string
     */
    public static function getDefault(): string
    {
        return self::FORMAT_STRING;
    }

    /**
     * All formats
     *
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::FORMAT_STRING,
            self::FORMAT_JSON,
            self::FORMAT_BOOL,
            self::FORMAT_INT,
            self::FORMAT_DOUBLE,
            self::FORMAT_ARRAY
        ];
    }

    /**
     * Check and get setting format
     *
     * @param string $format
     * @return string
     */
    public static function getFormat(string $format): string
    {
        return in_array($format, self::getAll()) ? $format : self::getDefault();
    }

    /**
     * Change value format
     *
     * @param $val
     * @param string $format
     * @return mixed
     */
    public static function toFormat($val, string $format)
    {
        $val = self::getVal($val);
        $isArray = gettype($val) === 'array';
        switch ($format) {
            case self::FORMAT_ARRAY:
                return $isArray? array_map(function ($item) {return json_decode($item, true);}, $val) : json_decode($val, true);
            case self::FORMAT_JSON:
                return $isArray? array_map(function ($item) {return json_decode($item);}, $val) : json_decode($val);
            case self::FORMAT_INT:
                return $isArray? array_map(function ($item) {return (int)$item;}, $val) : (int)$val;
            case self::FORMAT_DOUBLE:
                return $isArray? array_map(function ($item) {return (double)$item;}, $val) : (double)$val;
            case self::FORMAT_BOOL:
                return $isArray? array_map(function ($item) {return $item === self::VALUE_TRUE;}, $val) : $val === self::VALUE_TRUE;
            default:
                return $isArray? array_map(function ($item) {return (string)$item;}, $val) : (string)$val;
        }
    }

    private static function getVal($val)
    {
        if (gettype($val) === 'string' && str_starts_with($val, '{') && str_ends_with($val, '}')) {
            $val = json_decode($val, true);
        }
        return $val;
    }

    /**
     * Change value to string by format
     *
     * @param $val
     * @param string $format
     * @return string
     */
    public static function toString($val, string $format): string
    {
        $isArray = gettype($val) === 'array';
        switch ($format) {
            case self::FORMAT_ARRAY:
            case self::FORMAT_JSON:
                return json_encode($val);
            case self::FORMAT_STRING:
            case self::FORMAT_INT:
            case self::FORMAT_DOUBLE:
                return $isArray ? json_encode(array_map(function ($item) {return (string)$item;}, $val)) : (string)$val;
            case self::FORMAT_BOOL:
                return $isArray ? json_encode(array_map(function ($item) {return $item ? self::VALUE_TRUE : self::VALUE_FALSE;}, $val)) : ($val ? self::VALUE_TRUE : self::VALUE_FALSE);
        }
    }
}
