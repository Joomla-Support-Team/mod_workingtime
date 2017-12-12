<?php

/**
* @version      2.0.0 31.07.2017
* @author       shurikkan and woojin (Joomla-support.ru)
* @copyright    Copyright (c) 2017 Joomla-Support.ru. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die();

$layout     = $params->get('layout', 'default');
$message    = $params->get('message', '');

require_once dirname(__FILE__).'/helper.php';

$workingStatus = new ScheduleTimeWork();
$curTime = date('d.m.Y H:i');

require JModuleHelper::getLayoutPath('mod_workingtime', $layout);

/* test 1 */
?>