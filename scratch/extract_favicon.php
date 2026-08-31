<?php

$svgPath = __DIR__ . '/../public/new-store/images/Logo.svg';
$svg = file_get_contents($svgPath);

if (preg_match('/xlink:href="data:image\/png;base64,([^"]+)"/', $svg, $matches)) {
    $pngData = base64_decode($matches[1]);
    file_put_contents(__DIR__ . '/../public/favicon.png', $pngData);
    file_put_contents(__DIR__ . '/../public/favicon.ico', $pngData);
    file_put_contents(__DIR__ . '/../public/assets/images/k_favicon_32x.png', $pngData);
    file_put_contents(__DIR__ . '/../public/new-store/images/favicon.png', $pngData);
    copy($svgPath, __DIR__ . '/../public/favicon.svg');
    copy($svgPath, __DIR__ . '/../public/new-store/images/favicon.svg');
    echo "Saved favicon files successfully! Size: " . strlen($pngData) . " bytes\n";
} else {
    echo "Base64 image pattern not found in Logo.svg\n";
}
