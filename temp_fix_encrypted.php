<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$it = new RecursiveIteratorIterator($dir);
$pattern = '/route\(\s*([\'\"])([^\'\"]+)\1\s*,\s*\\->id\s*\)/';
$total = 0;
foreach ($it as $file) {
    if (! $file->isFile() || substr($file->getFilename(), -10) !== '.blade.php') continue;
    $path = $file->getPathname();
    $text = file_get_contents($path);
    if (!preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) continue;

    // collect variables in file
    preg_match_all('/\$([A-Za-z_][A-Za-z0-9_]*)/', $text, $varMatches);
    $vars = array_unique($varMatches[1]);

    $new = preg_replace_callback($pattern, function($m) use ($vars) {
        $route = $m[2];
        $parts = explode('.', $route);
        // prefer variable matching any part
        foreach (array_reverse($parts) as $part) {
            // try exact match
            if (in_array($part, $vars)) return "route('{$route}', \\${$part}->id)";
            // try singular form (strip trailing s)
            if (substr($part, -1) === 's') {
                $sing = substr($part, 0, -1);
                if (in_array($sing, $vars)) return "route('{$route}', \\${$sing}->id)";
            }
        }
        // try common names
        $candidates = ['item','model','data','row','p','d','l','tugas','laporan','peserta','pesertapkl','pembimbing','divisi','user','t','pb','p'];
        foreach ($candidates as $c) if (in_array($c, $vars)) return "route('{$route}', \\${$c}->id)";
        // fallback to first var
        if (count($vars)) return "route('{$route}', \\${$vars[0]}->id)";
        // if none, return route without param (best effort)
        return "route('{$route}')";
    }, $text, -1, $n);

    if ($n) {
        file_put_contents($path, $new);
        $total += $n;
        echo "Fixed $n occurrences in $path\n";
    }
}

echo "Done. Total fixed: $total\n";
