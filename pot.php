#!/usr/bin/php

<?php

require_once 'config.php';
require_once 'core/pointless.php';

$command = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL;

$pot = new pointless(array_slice($_SERVER['argv'], 2));

if(method_exists($pot, $command))
	$pot->$command();