<?php

require_once 'config.php';

$command = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL;

switch($command) {
	case 'gen':
		gen();
		break;
	case 'update':
		update();
		break;
	case 'version':
		version();
		break;
	case 'help':
	default:
		help();
}