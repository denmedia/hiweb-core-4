<?php

namespace hiweb\components\Fields;


///LOAD TYPES
use hiweb\core\Paths\PathsFactory;


$types_path = __DIR__ . '/Types';
if ( !file_exists($types_path) || !is_dir($types_path)) {
    console_warn('Can load types directory', __NAMESPACE__, $types_path)->debugStatus = true;
} else {
    $dir = opendir($types_path);
    while(false !== ($type_dir = readdir($dir))) {
        if (preg_match('/^(\.){1,2}$/i', $type_dir) > 0) continue;
        $type_dir = $types_path . '/' . $type_dir;
        get_file($type_dir)->include_files_by_name([ 'global_functions.php', 'functions.php', 'hooks.php', 'init.php' ]);
    }
}
