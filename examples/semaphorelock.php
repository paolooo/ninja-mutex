<?php

require '../vendor/autoload.php';

use NinjaMutex\Lock\SemaphoreLock;
use NinjaMutex\Mutex;

$uniq_id = 123;

sleep(3);

$lock = new SemaphoreLock();
$mutex = new Mutex($uniq_id, $lock);

if ($mutex->acquireLock(1000)) {
    // Do some very critical stuff
    echo 'technical stuff';
    // and release lock after you finish
    $mutex->releaseLock();
} else {
    throw new Exception('Unable to gain lock!');
}
