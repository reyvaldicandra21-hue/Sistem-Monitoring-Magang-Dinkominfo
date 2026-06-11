<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$it = new RecursiveIteratorIterator($dir);
$pattern = '/route\(\s*([\'\"])([^\'\"]+)\1\s*,\s*\\$([A-Za-z_][A-Za-z0-9_]*)->id\s*\)/';
$count = 0;
foreach ($it as $file) {
    if (! $file->isFile() || substr($file->getFilename(), -10) !== '.blade.php') {
        continue;
    }

    $path = $file->getPathname();
    $text = file_get_contents($path);
    $new = preg_replace_callback($pattern, function ($m) {
        return "route({$m[1]}{$m[2]}{$m[1]}, \\${$m[3]}->id)";
    }, $text, -1, $n);

    if (!empty($n)) {
        file_put_contents($path, $new);
        $count += $n;
    }
}
echo "Updated $count route() occurrences in Blade views.\n";
