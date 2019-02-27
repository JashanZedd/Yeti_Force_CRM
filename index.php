<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once 'include/ConfigUtils.php';
\App\Process::$startTime = microtime(true);
\App\Process::$requestMode = 'WebUI';

$webUI = new \App\WebUI();
$webUI->process();
require ROOT_DIRECTORY.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'index.php';
