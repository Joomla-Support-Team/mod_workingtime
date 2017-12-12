<?php

/**
* @version      2.0.0 31.07.2017
* @author       shurikkan and woojin (Joomla-support.ru)
* @copyright    Copyright (c) 2017 Joomla-Support.ru. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die();

class ScheduleTimeWork {

    protected $schedule_array   = array();
    protected $now              = 0;
    protected $weekDay          = -1;

    function __construct() {
        $this->now = new DateTime();
        $time_stamp = $this->now->getTimestamp();
        $this->weekDay = date("w", $time_stamp);
        $this->fill();
        return $this;
    }

    private function fill() {
        $day = $this->now->format('d');
        $month = $this->now->format('m');
        $year = $this->now->format('Y');

        $this->schedule_array = array(
            0 => array('start' => mktime(10, 03, 0, $month, $day, $year), 'finish' => mktime(23, 50, 0, $month, $day, $year)), //0 воскресенье
            1 => array('start' => mktime(10, 02, 0, $month, $day, $year), 'finish' => mktime(23, 50, 0, $month, $day, $year)), //1 понедельник
            2 => array('start' => mktime(10, 02, 0, $month, $day, $year), 'finish' => mktime(23, 50, 0, $month, $day, $year)), //2 вторник
            3 => array('start' => mktime(10, 02, 0, $month, $day, $year), 'finish' => mktime(23, 50, 0, $month, $day, $year)), //3 среда
            4 => array('start' => mktime(10, 02, 0, $month, $day, $year), 'finish' => mktime(23, 50, 0, $month, $day, $year)), //4 четверг
            5 => array('start' => mktime(10, 02, 0, $month, $day, $year), 'finish' => mktime(03, 00, 0, $month, $day, $year)), //5 пятница
            6 => array('start' => mktime(10, 02, 0, $month, $day, $year), 'finish' => mktime(03, 00, 0, $month, $day, $year)), //6 суббота
        );

        for ($index = 0; $index < 7; $index++) {
            $dts = clone($this->now);
            $dtf = clone($this->now);
            $start = $this->schedule_array[$index]['start'];
            $finish = $this->schedule_array[$index]['finish'];

            if ($index == 0) {
                $dts->setTimestamp($start);
                $dts->add(new DateInterval('P' . (7 - $this->weekDay) . 'D'));
                $dtf->setTimestamp($finish);
                $dtf->add(new DateInterval('P' . (7 - $this->weekDay) . 'D'));
            } elseif ($index == $this->weekDay) {
                $dts->setTimestamp($start);
                $dtf->setTimestamp($finish);
            } elseif ($index < $this->weekDay) {
                $dts->setTimestamp($start);
                $dts->sub(new DateInterval('P' . ($this->weekDay - $index) . 'D'));
                $dtf->setTimestamp($finish);
                $dtf->sub(new DateInterval('P' . ($this->weekDay - $index) . 'D'));
            } elseif ($index > $this->weekDay) {
                $dts->setTimestamp($start);
                $dts->add(new DateInterval('P' . ($index - $this->weekDay) . 'D'));
                $dtf->setTimestamp($finish);
                $dtf->add(new DateInterval('P' . ($index - $this->weekDay) . 'D'));
            }

            if ($dts > $dtf) {
                $dtf->add(new DateInterval('P1D'));
            }

            $this->schedule_array[$index]['start'] = $dts->getTimestamp();
            $this->schedule_array[$index]['finish'] = $dtf->getTimestamp();
        }
    }

    private function getSchedule($weekDay) {
        $ret['now'] = $this->schedule_array[$weekDay];

        if ($weekDay == 0) {
            $ret['before'] = $this->schedule_array[6];
        } else {
            $ret['before'] = $this->schedule_array[$weekDay - 1];
        }

        return $ret;
    }

    private function getNowSchedule($schedule_now) {
        return array($schedule_now['start'], $schedule_now['finish']);
    }

    private function dayThisWeek($time_stamp_now) {
        $start_week = $this->schedule_array[1]['start'];
        $finish_week = $this->schedule_array[0]['finish'];

        if ($start_week < $time_stamp_now && $finish_week > $time_stamp_now) {

        } else {
            $this->fill();
        }
    }

    public function workTime($time = NULL, $text = FALSE) {
        if (!is_null($time)) {
            $this->now = new DateTime($time);
            $time_stamp = $this->now->getTimestamp();
            $this->weekDay = date("w", $time_stamp);
        } else {
            $time_stamp = $this->now->getTimestamp();
        }

        $this->dayThisWeek($time_stamp);

        $schedule_now = $this->getSchedule($this->weekDay);

        $schedul_now = $this->getNowSchedule($schedule_now['now']);
        $schedul_before = $this->getNowSchedule($schedule_now['before']);

        $begin_now = $schedul_now[0];
        $end_now = $schedul_now[1];

        $begin_before = $schedul_before[0];
        $end_before = $schedul_before[1];

        $bool_now = $time_stamp > $begin_now && $time_stamp < $end_now;
        $bool_before = $time_stamp > $begin_before && $time_stamp < $end_before;

        if ($bool_now || $bool_before) {
            $ret = TRUE;
        } else {
            $ret = FALSE;
        }

        if ($text) {
            return $ret ? 1 : 0;
        } else {
            return $ret;
        }
    }

}

?>