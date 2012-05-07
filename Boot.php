<?php
/**
 * Load NanoCLI and Setting
 */
require_once CORE . 'NanoCLI.php';
require_once CORE . 'Text.php';
require_once CORE . 'Autoload.php';

// Default Setting
define('NANOCLI_COMMAND', ROOT . 'Command' . SEPARATOR);
define('NANOCLI_PREFIX', 'pointless');

spl_autoload_register('NanoCLI_Autoload');

// Load First Command and Init
require_once NANOCLI_COMMAND . 'pointless.php';

$NanoCLI = new pointless();
$NanoCLI->Init();
