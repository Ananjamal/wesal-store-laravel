<?php
header('Content-Type: text/plain; charset=utf-8');

echo "=== WISAL STORE DEPLOYMENT DIAGNOSTICS ===\n\n";
echo "Current path: " . getcwd() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n\n";

echo "--- GIT STATUS ---\n";
echo shell_exec('git status 2>&1') . "\n";

echo "--- GIT RECENT COMMITS ---\n";
echo shell_exec('git log -n 5 --oneline 2>&1') . "\n";

echo "--- NODE & NPM VERSIONS ---\n";
echo "Node: " . trim(shell_exec('node -v 2>&1') ?: 'Not available') . "\n";
echo "NPM: " . trim(shell_exec('npm -v 2>&1') ?: 'Not available') . "\n\n";

echo "--- BUILD ASSETS CHECK ---\n";
$buildPath = __DIR__ . '/build';
if (is_dir($buildPath)) {
    echo "public/build directory exists.\n";
    $files = scandir($buildPath);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file (" . (is_dir($buildPath . '/' . $file) ? 'dir' : 'file') . ")\n";
        }
    }
} else {
    echo "public/build directory does NOT exist.\n";
}
