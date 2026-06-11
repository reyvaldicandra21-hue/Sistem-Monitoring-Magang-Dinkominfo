<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$it = new RecursiveIteratorIterator($dir);
$files = [];
foreach ($it as $file) {
    if ($file->isFile() && substr($file->getFilename(), -10) === '.blade.php') {
        $path = $file->getPathname();
        $text = file_get_contents($path);
        if (strpos($text, '\\->id') !== false) {
            $files[] = $path;
        }
    }
}
$total=0;
foreach ($files as $path) {
    $text = file_get_contents($path);
    preg_match_all('/\$([A-Za-z_][A-Za-z0-9_]*)/', $text, $vm);
    $vars = array_filter($vm[1], function($v){ return !in_array($v, ['__env','loop','errors','message','attributes']); });
    if (empty($vars)) continue;
    // pick most frequent
    $freq = array_count_values($vars);
    arsort($freq);
    $var = array_key_first($freq);
    $new = str_replace('\\->id', '$'.$var.'->id', $text);
    if ($new !== $text) {
        file_put_contents($path, $new);
        $count = substr_count($text, '\\->id');
        $total += $count;
        echo "Replaced $count occurrences in $path with ".$var."\n";
    }
}
echo "Done. Total replaced: $total\n";
