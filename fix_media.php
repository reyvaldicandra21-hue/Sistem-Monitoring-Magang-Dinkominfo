<?php

$cssFile = __DIR__ . '/public/css/custom.css';
$cssContent = file_get_contents($cssFile);

// The issue is a missing } before @media (max-width: 480px)
// Let's replace all occurrences of this specific pattern:
//     .profile-info {
//         display: none;
//     }
// 
// 
// @media (max-width: 480px) {

$search = "    .profile-info {\n        display: none;\n    }\n\n\n@media (max-width: 480px) {";
$replace = "    .profile-info {\n        display: none;\n    }\n}\n\n@media (max-width: 480px) {";

$cssContent = str_replace($search, $replace, $cssContent);

// Wait, the newlines might be different. Let's use regex.
$cssContent = preg_replace('/(\.profile-info\s*\{\s*display:\s*none;\s*\})\s*@media\s*\(\s*max-width:\s*480px\s*\)\s*\{/s', "$1\n}\n\n@media (max-width: 480px) {", $cssContent);

file_put_contents($cssFile, $cssContent);
echo "Fixed missing } before @media (max-width: 480px)\n";

?>
