<?php
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
