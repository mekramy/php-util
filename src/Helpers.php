<?php

namespace MEkramy\PHPUtil;

use DateTime;
use Exception;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

/**
 * Helpers function
 *
 * @author m ekramy <m@ekramy.ir>
 * @access public
 * @version 1.0.0
 */
class Helpers
{

    /**
     * Make decision by associative array
     * use array key as return value and array value as condition
     * callable value allowed
     *
     * @param array $conditions                                 associative array of conditions
     * @param mixed $default                                    default value returned if no condition matched
     * @return mixed
     */
    public static function quickSwitch(array $conditions, $default = null)
    {
        foreach ($conditions as $return => $condition) {
            if (is_callable($condition) ? $condition() : $condition) return $return;
        }

        return $default;
    }

    /**
     * Get persian date
     *
     * @param string|Carbon|DateTime|timestamp $date            UTC date to convert. null return current time
     * @param ?string $format                                   string format for formatting output, null will return date object
     * @return string|Verta|null
     */
    public static function toPersianDate($date = null, ?string $format = 'Y-m-d H:i:s')
    {
        try {
            $persianDate = new Verta($date);
            return is_null($format) ? $persianDate : $persianDate->format($format);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get gregorian date
     *
     * @param string|\Hekmatinasser\Verta\Verta $date           Persian date to convert. null return current time
     * @param ?string $format                                   string format for formatting output, null will return date object
     * @return string|\Carbon\Carbon|null
     */
    public static function toGregorianDate($date = null, ?string $format = 'Y-m-d H:i:s')
    {
        try {
            $gregorianDate = Carbon::now();
            if ($date !== null) {
                if ($date instanceof Verta) {
                    $gregorianDate = Carbon::instance($date->DateTime());
                } else {
                    $dt = Verta::parse($date);
                    $gregorianDate = Carbon::instance($dt->DateTime());
                }
                return is_null($format) ? $gregorianDate : $gregorianDate->format($format);
            }
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Validate value and check against valid value arrays
     *
     * @param mixed $value                                      value to validate
     * @param array $allowed                                    list of allowed values. pass null to ignore check
     * @param mixed $default                                    return if value not valid or empty
     * @return mixed                                            $value if is valid $default otherwise
     */
    public static function validateOrDefault($value, ?array $allowed, $default = null)
    {
        return empty($value) || (is_array($allowed) && !in_array($value, $allowed)) ? $default : $value;
    }

    /**
     * Validate numeric value and check against valid value arrays, minimum and maximum
     *
     * @param mixed $value                                      value to validate
     * @param bool $float                                       parse as float or int
     * @param int|float	$min                                    minimum value. pass null to ignore check
     * @param int|float $max                                    maximum value. pass null to ignore check
     * @param array $allowed                                    list of allowed values. pass null to ignore check
     * @param int|float                                         $default return if value not valid or empty
     * @return number
     */
    public static function validateNumberOrDefault($value, bool $float = false, $min = null, $max = null, ?array $allowed = null, $default = null)
    {
        $res = $default;
        if (is_numeric($value)) {
            $parseFn = $float === true ? 'floatval' : 'intval';
            $res = $parseFn($value);
            if (
                (is_numeric($min) && $res < $parseFn($min)) ||
                (is_numeric($max) && $res > $parseFn($max)) ||
                (is_array($allowed) && !in_array($res, $allowed))
            ) {
                $res = $default;
            }
        }
        return $res;
    }

    /**
     * Parse value as boolean
     *
     * @param mixed $value                                      value to parse
     * @return bool
     */
    public static function asBoolean($value): bool
    {
        return $value === 1 || $value === "1" || $value === true || $value === "true" || $value === "on" || $value === "yes";
    }

    /**
     * Extract numbers from formatted value
     *
     * @param mixed $value                                      value to process
     * @return string
     */
    public static function extractNumbers($value): string
    {
        preg_match_all('!\d+!', $value, $matches);
        return implode('', $matches[0]);
    }

    /**
     * Generate format string with placeholder
     * use {placeholder} to make placeholder
     * for non associative args array use numeric placeholder and it started from 0
     *
     * @param string $pattern                                   string pattern
     * @param array $args                                       argument to placed in placeholder
     *
     * @return string                                           formatted string
     */
    public static function formatString(string $pattern, array $args): string
    {
        $res = $pattern;
        $args = is_array($args) ? $args : [$args];
        foreach ($args as $key => $value) {
            $placeholder = "{{$key}}";
            $res = str_replace($placeholder, $value, $res);
        }
        return $res;
    }

    /**
     * Print a debug block in json format text
     *
     * @param mixed $var                                        variable to print
     * @param string $header                                    header to show as debug title
     * @param string $separator                                 separator character
     * @param int $length                                       separator length
     * @return void
     */
    public static function printDebug($var, string $header = 'debug', string $separator = '=', int $length = 50): void
    {
        $header = strtoupper(" $header ");
        $header = str_pad($header, $length, $separator, STR_PAD_BOTH);
        $footer = str_repeat($separator, $length);
        echo "\n{$header}\n";
        echo json_encode($var, JSON_PRETTY_PRINT);
        echo "\n{$footer}\n";
    }
}
