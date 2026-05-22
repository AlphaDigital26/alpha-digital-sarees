<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$colors = [
    1 => '#FFC0CB',
    2 => '#FFFF00',
    3 => '#F88379',
    4 => '#FFDAB9',
    5 => '#00BFFF',
    6 => '#20B2AA',
    7 => '#FFEA00',
    8 => '#87CEEB',
    9 => '#FFE5B4',
    10 => '#E6E6FA',
    11 => '#B0E0E6',
    12 => '#AFEEEE',
    13 => '#7FFFD4',
    14 => '#367588',
    15 => '#9DC183',
    16 => '#FFFDD0',
    17 => '#8B4513',
    18 => '#C2A5B8'
];
foreach($colors as $id => $hex) {
    App\Models\Color::where('id', $id)->update(['hex_code' => $hex]);
}
echo "Done\n";
