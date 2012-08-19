<?php
/**
 * Load NanoCLI and Setting
 */
require_once CORE . 'NanoCLI/NanoCLI.php';
require_once CORE . 'NanoCLI/NanoIO.php';
require_once CORE . 'NanoCLI/NanoLoader.php';

// Default Setting
define('NANOCLI_COMMAND', ROOT . 'Command' . SEPARATOR);
define('NANOCLI_PREFIX', 'pointless');

// Register NanoCLI Autoloader
NanoLoader::Register();

// Load First Command and Init
require_once NANOCLI_COMMAND . 'pointless.php';

$NanoCLI = new pointless();
$NanoCLI->Init();
