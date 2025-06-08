<?php
echo "Loaded extensions:<br>";
if (extension_loaded('sqlsrv')) {
    echo "✓ SQLSRV extension is loaded<br>";
} else {
    echo "✗ SQLSRV extension is NOT loaded<br>";
}

if (extension_loaded('pdo_sqlsrv')) {
    echo "✓ PDO_SQLSRV extension is loaded<br>";
} else {
    echo "✗ PDO_SQLSRV extension is NOT loaded<br>";
}

echo "<br>All loaded extensions:<br>";
print_r(get_loaded_extensions());
?>