<?php
echo "Testing SQLSRV functions...<br><br>";


if (function_exists('sqlsrv_connect')) {
    echo "✓ sqlsrv_connect function exists<br>";
} else {
    echo "✗ sqlsrv_connect function does NOT exist<br>";
}

if (function_exists('sqlsrv_errors')) {
    echo "✓ sqlsrv_errors function exists<br>";
} else {
    echo "✗ sqlsrv_errors function does NOT exist<br>";
}

if (function_exists('sqlsrv_close')) {
    echo "✓ sqlsrv_close function exists<br>";
} else {
    echo "✗ sqlsrv_close function does NOT exist<br>";
}

echo "<br>Extensions loaded:<br>";
if (extension_loaded('sqlsrv')) {
    echo "✓ SQLSRV extension is loaded<br>";
} else {
    echo "✗ SQLSRV extension is NOT loaded<br>";
}


echo "<br>Available SQLSRV functions:<br>";
$functions = get_extension_funcs('sqlsrv');
if ($functions) {
    foreach ($functions as $func) {
        echo "- " . $func . "<br>";
    }
} else {
    echo "No SQLSRV functions found<br>";
}
?>