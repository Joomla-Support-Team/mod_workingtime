<?php

/**
* @version      2.0.0 31.07.2017
* @author       shurikkan and woojin (Joomla-support.ru)
* @copyright    Copyright (c) 2017 Joomla-Support.ru. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die();

$status = $workingStatus->workTime($curTime, false);

if(!$status) { ?>

<div class="row workingtime">

    <?php 
	printf($message, ''); // Допилить вывод времени открытия со следующего рабочего дня
	?>
    
</div>

<?php } ?>