<?php
/**
 * Load NanoCLI and Setting
 */
require_once CORE . 'NanoLoader.php';
require_once CORE . 'NanoIO.php';
require_once CORE . 'NanoCLI.php';

// Default Setting
define('NANOCLI_COMMAND', ROOT . 'Command' . SEPARATOR);
define('NANOCLI_PREFIX', 'pointless');

// Register NanoCLI Autoloader
NanoLoader::Register();

// Load First Command and Init
require_once NANOCLI_COMMAND . 'pointless.php';

$NanoCLI = new pointless();
$NanoCLI->Init();
