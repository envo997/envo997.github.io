<?php
require_once 'autoload.php';

$input_data = "";
$format_input = "json";
$format_output = "csv";
$result = "";

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    if (isset($_POST['input_data'])) {
        $input_data = $_POST['input_data'];
        $format_input = $_POST['format_input'] ?? "json";
        $format_output = $_POST['format_output'] ?? "csv";
    }
    setcookie('saved_input', $input_data, time() + (86400 * 30), "/");
    setcookie('saved_format_in', $format_input, time() + (86400 * 30), "/");
    setcookie('saved_format_out', $format_output, time() + (86400 * 30), "/");

    $serializer = new Serializer();
    $result = $serializer->convert($input_data, $format_input, $format_output);
} else {
    $input_data = "";
}

require_once 'templates/layout.php';