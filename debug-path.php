<?php
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/config/db.php';
echo "--- Database Config BASE_URL: " . BASE_URL . " ---\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "DIRNAME __DIR__: " . dirname(__DIR__) . "\n";
echo "REALPATH __DIR__: " . realpath(dirname(__DIR__)) . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";

$project_root = str_replace('\\', '/', dirname(__DIR__));
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$rel_path = str_ireplace($doc_root, '', $project_root);
echo "CALCULATED REL_PATH: " . $rel_path . "\n";

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
echo "BASE_URL (Calculated): " . $protocol . "://" . $host . "/" . trim($rel_path, '/') . "\n";
