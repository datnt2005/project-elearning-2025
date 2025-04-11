<?php

function renderViewUser($view, $data = [], $title = "My App") {
    // ra bien tu bang thanh don
    extract($data);
    ob_start();
    require $view;
    $content = ob_get_clean();
    require "view/layouts/master_user.php";
}

function renderViewAdmin($view, $data = [], $title = "My App") {
    extract($data);
    ob_start();
    require $view;
    $content = ob_get_clean();
    require "view/layouts/master_admin.php";
}

// chuẩn hoá  không phân biệt hoa thường, không dư khoảng trắng.
function removeVietnameseTones($text) {
    return strtolower(trim(preg_replace('/\s+/', ' ', $text)));
}

?>