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

$dispatcher = new \Anonym\Components\Event\EventDispatcher();
$dispatcher->listen(
    'test',
    function () {
        return 'hello world';
    }
);

$response = $dispatcher->fire('test');
var_dump($response);



