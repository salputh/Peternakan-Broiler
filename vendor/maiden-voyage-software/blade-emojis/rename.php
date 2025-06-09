<?php

/*
 * This script renames icons from the source SVG's to human readable format
 */
$emojiData = json_decode(file_get_contents('https://raw.githubusercontent.com/muan/unicode-emoji-json/refs/heads/main/data-by-emoji.json'), true);
$directory = __DIR__.'/resources/test';

// Get filename from an emoji string
function getFilenameFromEmoji(string $emoji): string
{
    $codepoints = [];
    foreach (mb_str_split($emoji) as $char) {
        $codepoints[] = sprintf('%04x', mb_ord($char, 'UTF-8'));
    }

    return 'emoji_u'.implode('_', $codepoints).'.svg';
}

// Build full filename → slug mapping (including skin tone variants)
$filenameToSlug = [];

foreach ($emojiData as $emoji => $data) {
    $baseFilename = getFilenameFromEmoji($emoji);
    $baseSlug = str_replace('_', '-', $data['slug']).'.svg';
    $filenameToSlug[$baseFilename] = $baseSlug;

    // Handle skin tone variations if present
    if (isset($data['skins'])) {
        foreach ($data['skins'] as $variant) {
            $variantEmoji = $variant['emoji'];
            $variantFilename = getFilenameFromEmoji($variantEmoji);
            $variantSlug = str_replace('_', '-', $variant['slug']).'.svg';
            $filenameToSlug[$variantFilename] = $variantSlug;
        }
    }
}

// Rename SVG files
$files = glob($directory.'/emoji_u*.svg');
foreach ($files as $filePath) {
    $filename = basename($filePath);
    if (isset($filenameToSlug[$filename])) {
        $newPath = $directory.'/'.$filenameToSlug[$filename];
        if (! file_exists($newPath)) {
            rename($filePath, $newPath);
            echo "Renamed: $filename → ".$filenameToSlug[$filename].PHP_EOL;
        } else {
            echo 'Skipped (exists): '.$filenameToSlug[$filename].PHP_EOL;
        }
    } else {
        echo "No mapping for: $filename".PHP_EOL;
    }
}
