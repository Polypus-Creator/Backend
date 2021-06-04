<?php
header('Content-Type: application/json');
$_GET = json_decode(file_get_contents('php://input'), true);
$_POST = array_merge($_POST, (array)json_decode(file_get_contents('php://input')));
