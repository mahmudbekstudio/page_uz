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
     * @param string $val
     * @param string $format
     * @return mixed
     */
    public static function toFormat(string $val, string $format)
    {
        switch ($format) {
            case self::FORMAT_ARRAY:
                return json_decode($val, true);
            case self::FORMAT_JSON:
                return json_decode($val);
            case self::FORMAT_INT:
                return (int)$val;
            case self::FORMAT_DOUBLE:
                return (double)$val;
            case self::FORMAT_BOOL:
                return $val === self::VALUE_TRUE;
            default:
                return (string)$val;
        }
    }

    /**
     * Change value to string by format
     *
     * @param string $val
     * @param string $format
     * @return string
     */
    public static function toString(string $val, string $format): string
    {
        switch ($format) {
            case self::FORMAT_ARRAY:
            case self::FORMAT_JSON:
                return json_encode($val);
            case self::FORMAT_STRING:
            case self::FORMAT_INT:
            case self::FORMAT_DOUBLE:
                return $val;
            case self::FORMAT_BOOL:
                return $val ? self::VALUE_TRUE : self::VALUE_FALSE;
        }
    }
}
