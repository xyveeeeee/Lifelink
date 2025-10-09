<?php
//this file is for debuuging to be not confused
$expected = __DIR__ . '/../html/reset_password.php';

echo "<h2>Path check</h2>";
echo "<p>Expected file path: <code>$expected</code></p>";

if (file_exists($expected)) {
    echo "<p style='color:green;font-weight:bold;'>FOUND: reset_password.php exists.</p>";
    echo "<pre>" . htmlspecialchars(realpath($expected)) . "</pre>";
} else {
    echo "<p style='color:red;font-weight:bold;'>NOT FOUND: reset_password.php does not exist at that path.</p>";

    $dir = __DIR__ . '/../html';
    echo "<p>Listing of {$dir}:</p><pre>";
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $f) {
            echo htmlspecialchars($f) . PHP_EOL;
        }
    } else {
        echo "Directory not found: " . htmlspecialchars($dir);
    }
    echo "</pre>";
}
