<?php
function slugify($text) {
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace(['ı','ş','ğ','ü','ö','ç','İ','Ş','Ğ','Ü','Ö','Ç'], ['i','s','g','u','o','c','i','s','g','u','o','c'], $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function formatPrice($price) {
    return number_format((float)$price, 2, ',', '.');
}

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
}
