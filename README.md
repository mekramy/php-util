# Helpers And Utility Functions For PHP

### Quick Switch

If you need to check many conditions to set a variable value you can use `quickSwitch` instead of deep ternary (?:), if/else or switch/case.

```php
use MEkramy\PHPUtil\Helpers;

$res = Helpers::quickSwitch([
    'First Value' => false,
    'Second Value' => function(){
        return false;
    },
    'Third Value' => falsyFunction(),
    'True Option' => true
], 'Default Value');
echo $res; # > "True Option"

$res = Helpers::quickSwitch([
    'First Value' => false,
    'Second Value' => function(){
        return false;
    },
    'Third Value' => falsyFunction(),
], 'Default Value');
echo $res; # > "Default Value"
```

Quick switch simply accept a associative array of `value => condition`. if any of conditions is truly or return truly value, returned as result otherwise the default value will returned.


### Convert Date To Persian Date

You can pass `string` date, `DateTime` object, `Carbon` instance or `timestamp` to parse as persian date.

```php
static function toPersianDate($date = null, ?string $format = 'Y-m-d H:i:s')
```

**Note:** If you pass `null` as input date, this function return current date

**Note** If you pass `null` as format parameter, this function return `\Hekmatinasser\Verta\Verta` object instead of date `string`.

**Note** If invalid date passed as input this function return `null`

```php
use MEkramy\PHPUtil\Helpers;

$gregorian_date = '2019-03-21';
$res = Helpers::toPersianDate($gregorian_date); # > 1398-01-01 00:00:00
$res = Helpers::toPersianDate($gregorian_date, 'Y'); # > 1398
$res = Helpers::toPersianDate($gregorian_date, null); # > \Hekmatinasser\Verta\Verta instance
```

### Convert Persian Date To Date

You can pass `string` persian date or `\Hekmatinasser\Verta\Verta` instance to parse as gregorian date.

```php
static function toPersianDate($date = null, ?string $format = 'Y-m-d H:i:s')
```

**Note:** If you pass `null` as input date this function return current date

**Note** If you pass `null` as format parameter this function return `\Carbon\Carbon` object instead of date `string`.

**Note** If invalid date passed as input this function return `null`

```php
use MEkramy\PHPUtil\Helpers;

$persian_date = '1398-01-01';
$res = Helpers::toGregorianDate($persian_date); # > 2019-03-21 00:00:00
$res = Helpers::toGregorianDate($persian_date, 'Y'); # > 2019
$res = Helpers::toGregorianDate($persian_date, null); # > \Carbon\Carbon instance
```


### Validate Value

Check if value not empty and is a valid value (if allowed list passed) or return default value if not valid.

```php
static function validateOrDefault($value, ?array $allowed, $default = null)
```

**Note** Pass `null` as allowed values to ignore check

```php
use MEkramy\PHPUtil\Helpers;

$res = Helpers::validateOrDefault(null, null, 'default'); # > "default"
$res = Helpers::validateOrDefault("my val", null, 'default'); # > "my val"
$res = Helpers::validateOrDefault("test", ["first valid value", "second"], 'default'); # > "default"
```

### Validate Number

Check if value is a number and is in range (min/max) and is valid (allowed value) or return default.

```php
public static function validateNumberOrDefault($value, bool $float = false, $min = null, $max = null, ?array $allowed = null, $default = null)
```

**Note** If `$float` set to true `$value` parsed as `float` otherwise parsed as `int`

**Note** Pass `null` as allowed values to ignore check

**Note** Pass `null` as min/max values to ignore check

```php
use MEkramy\PHPUtil\Helpers;

$res = Helpers::validateNumberOrDefault(12, false, null, null, null, 0); # > 12
$res = Helpers::validateNumberOrDefault(12, true, 12.001, null, null, 0); # > 0
$res = Helpers::validateNumberOrDefault("12.03", true, 11.99, 12.99, null, 0); # > 12.03
$res = Helpers::validateNumberOrDefault(7, true, null, null, [1, 2, 3, 4], 0); # > 0
```

### Convert Value To Boolean

If value is `1`, `"1"`, `true`, `"true"`, `"on"` or `"yes"` return `true` otherwise return `false`

```php
use MEkramy\PHPUtil\Helpers;

$res = Helpers::asBoolean("1"); # > true
$res = Helpers::asBoolean(true); # > true
$res = Helpers::asBoolean("on"); # > true
$res = Helpers::asBoolean("yes"); # > true

$res = Helpers::asBoolean(null); # > false
$res = Helpers::asBoolean("-"); # > false
```

### Extract Number From String

```php
use MEkramy\PHPUtil\Helpers;

$res = Helpers::extractNumbers("0123- 456"); # > "0123456"
$res = Helpers::extractNumbers("1 this is a text 2 contains3 string"); # > "123"
$res = Helpers::extractNumbers("text with no numbers"); # > ""
```

### Format String With Placeholder

```php
use MEkramy\PHPUtil\Helpers;

$res = Helpers::formatString("{0} - {1}", ['abc', 'def']); # > "abc - def"
$res = Helpers::formatString("{first} {last}", ['first' => 'John', 'last' => 'Doe']); # > "John Doe"
$res = Helpers::formatString("you search {search} and we found no result for {search}", ['search' => 'dummy']); # > "you search dummy and we found no result for dummy"
```

### Print Debug Block

This function convert input variable to JSON pretty formatted and print to output using echo with headers and footer.

**Note** You can specify header text, separator character and header/footer length

```php
use MEkramy\PHPUtil\Helpers;

$a = [
    "first_name" => "john",
    "last_name" => "doe",
    "address" => [
        "city" => "Somewhere",
        "street" => null
    ]
];

Helpers::printDebug($a, "john doe info", "*", 75);

# ****************************** JOHN DOE INFO ******************************
# {
#     "first_name": "john",
#     "last_name": "doe",
#     "address": {
#         "city": "Somewhere",
#         "street": null
#     }
# }
# ***************************************************************************

Helpers::printDebug("simple value", "simple", "-", 25);

# -------- SIMPLE ---------
# "simple value"
# -------------------------

Helpers::printDebug(pi());

# ===================== DEBUG ======================
# 3.141592653589793
# ==================================================
```
