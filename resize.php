<?php
$files = ['public/images/heritage-main.jpg', 'public/images/heritage-sub.jpg'];
foreach($files as $src) {
    if(!file_exists($src)) {
        echo "File not found: $src\n";
        continue;
    }
    
    // Check type
    $info = getimagesize($src);
    if($info['mime'] == 'image/jpeg') {
        $img = imagecreatefromjpeg($src);
    } elseif($info['mime'] == 'image/png') {
        $img = imagecreatefrompng($src);
    } else {
        echo "Unsupported type for $src\n";
        continue;
    }
    
    $w = imagesx($img);
    $h = imagesy($img);
    
    if($w <= 1000 && $h <= 1000) {
        echo "Already small enough: $src\n";
        continue;
    }
    
    // Calculate new dimensions (max 1000px on longest side)
    if($w > $h) {
        $new_w = 1000;
        $new_h = floor($h * (1000 / $w));
    } else {
        $new_h = 1000;
        $new_w = floor($w * (1000 / $h));
    }
    
    $dest = imagecreatetruecolor($new_w, $new_h);
    
    // Handle transparency for PNG
    if($info['mime'] == 'image/png') {
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        $transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
        imagefilledrectangle($dest, 0, 0, $new_w, $new_h, $transparent);
    }
    
    // Resample smoothly
    imagecopyresampled($dest, $img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
    
    $destName = str_replace('.jpg', '-opt.jpg', $src);
    
    // Save back to new file
    if($info['mime'] == 'image/jpeg') {
        imagejpeg($dest, $destName, 85); // 85 quality is a good balance
    } elseif($info['mime'] == 'image/png') {
        $destName = str_replace('.png', '-opt.png', $src);
        imagepng($dest, $destName, 8);
    }
    
    imagedestroy($img);
    imagedestroy($dest);
    
    echo "Successfully optimized: $destName\n";
}
