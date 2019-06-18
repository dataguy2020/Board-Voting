<?php
date_default_timezone_set('America/New_York');

$script_tz = date_default_timezone_get();

if (strcmp($script_tz, ini_get('date.timezone'))){
    echo 'Script timezone differs from ini-set timezone.';
} else {
    echo 'Script timezone and ini-set timezone match.';
}

$today = date("Y-m-d H:i:s");
echo $today;

echo "<br /><br />";

echo date('Y-m-d H:i:s T', time()) . "<br>\n";
date_default_timezone_set('UTC');
echo date('Y-m-d H:i:s T', time()) . "<br>\n";

?>
