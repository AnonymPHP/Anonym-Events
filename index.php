<?php
/**
 * This file belongs to the AnoynmFramework
 *
 * @author vahitserifsaglam <vahit.serif119@gmail.com>
 * @see http://gemframework.com
 *
 * Thanks for using
 */

include 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$event = new \Anonym\Components\Event\EventDispatcher();

$event->listen('before_save', function($data){
    var_dump($data);
});

$event->listen('before_save', function ($data1){
    var_dump($data1. 'data');
});

$event->fire('before_save', ['data1']);