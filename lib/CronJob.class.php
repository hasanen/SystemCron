<?php
class CronJob{
 
	private $minute;
	private $hour;
	private $dayOfMonth;
	private $month;
	private $dayOfWeek;
	private $job;
 
	public function __construct($minute, $hour, $dayOfMonth, $month, $dayOfWeek, $job){
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
		$this->minute = $minute;
	}
	public function setHour($hour){
		$this->hour = $hour;
	}
	public function setDayOfMonth($dayOfMonth){
		$this->dayOfMonth = $dayOfMonth;
	}
	public function setMonth($month){
		$this->month = $month;
	}
	public function setDayOfWeek($dayOfWeek){
		$this->dayOfWeek = $dayOfWeek;
	}
	public function setJob($job){
		$this->job = $job;
	}
}
?>
