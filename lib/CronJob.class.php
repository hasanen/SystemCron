<?php
#-------------------------------------------------------------------------
#
# Name: CronJob
# Description: Simple object type class
#
#-------------------------------------------------------------------------
#
# Copyright (C) 2012  Joni Hasanen 
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#-------------------------------------------------------------------------
class CronJob{
 
	private $minute;
	private $hour;
	private $dayOfMonth;
	private $month;
	private $dayOfWeek;
	private $job;
 
	public function __construct($job, $minute = '', $hour = '', $dayOfMonth = '', $month = '', $dayOfWeek = ''){
		$this->setMinute($minute);
		$this->setHour($hour);
		$this->setDayOfMonth($dayOfMonth);
		$this->setMonth($month);
		$this->setDayOfWeek($dayOfWeek);
		$this->setJob($job);
	}
 
	 /* Getters */
	public function getMinute(){
		return $this->minute;
	}
	public function getHour(){
		return $this->hour;
	}
	public function getDayOfMonth(){
		return $this->dayOfMonth;
	}
	public function getMonth(){
		return $this->month;
	}
	public function getDayOfWeek(){
		return $this->dayOfWeek;
	}
	public function getJob(){
		return $this->job;
	}
 
	 /* Setters */
	public function setMinute($minute){
        if(empty($minute)){
            $minute = '*';
        }
		$this->minute = $minute;
	}
	public function setHour($hour){
        if(empty($hour)){
            $hour = '*';
        }
		$this->hour = $hour;
	}
	public function setDayOfMonth($dayOfMonth){
        if(empty($dayOfMonth)){
            $dayOfMonth = '*';
        }
		$this->dayOfMonth = $dayOfMonth;
	}
	public function setMonth($month){
        if(empty($month)){
            $month = '*';
        }
		$this->month = $month;
	}
	public function setDayOfWeek($dayOfWeek){
        if(empty($dayOfWeek)){
            $dayOfWeek = '*';
        }
		$this->dayOfWeek = $dayOfWeek;
	}
	public function setJob($job){
		$this->job = $job;
	}
    
    public function __toString(){
        return sprintf('%s %s %s %s %s %s',
        $this->getMinute(),
        $this->getHour(),
        $this->getDayOfMonth(),
        $this->getMonth(),
        $this->getDayOfWeek(),
        $this->getJob());
    }
}
?>
