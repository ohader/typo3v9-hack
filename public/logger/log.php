<?php
header('Cache-Control: no-cache');
if (!empty($_GET['u']) & !empty($_GET['l'])) {
    $line = sprintf(
        '[%s] %s - %s :: %s',
        (new \DateTime())->format('c'),
        $_SERVER['REMOTE_ADDR'] ?? 'none',
        $_GET['u'],
        $_GET['l']
    ) . PHP_EOL;
    file_put_contents(__DIR__ . '/log.txt', $line, FILE_APPEND);
}