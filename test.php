<?php
require 'vendor/autoload.php';  // 自动加载 Composer 包

use Hejunjie\Tools;

echo json_encode(Tools\IdAddressLookup::getAll(), JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRESERVE_ZERO_FRACTION);
