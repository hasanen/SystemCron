<?php
#-------------------------------------------------------------------------
#
# Name: Cron
# Description: Provide few methods for access crontab via PHP
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
class Cron{
 
    function __construct(){
        $missingPhp = $this->getMissingFunctions();
        $missingSystem = $this->getMissinSystemCommands();
        
        $errorMsg  = '';
        if(count($missingSystem) > 0){
            $errorMsg = sprintf('Following commands doesn\'t exists or process haven\'t permissions to use it: %s', implode(', ', $missingSystem));
        }
        if(count($missingPhp) > 0){
            if(strlen($errorMsg) > 0){
                $errorMsg .= '. ';
            }
            $errorMsg .= sprintf('Following functions doesn\'t exists: %s', implode(', ', $missingPhp));
        }
        if(strlen($errorMsg) > 0){
            echo $errorMsg;
            throw new Exception($errorMsg);
        }
    }
    
    /**
     * Test if necessary functions exists
     */
    function getMissingFunctions(){
        $missingCommands = array();   
        if(!function_exists('shell_exec')){
            array_push($missingCommands, 'shell_exec');
        }
        return $missingCommands;
    }
    /**
     * Test if necessary programs/commands found from system
     */
    function getMissinSystemCommands(){  
        $missingCommands = array();   
           
        if(!$this->testCommand('crontab -l')){
            array_push($missingCommands, 'crontab');
        }
        
        if(!$this->testCommand('whoami')){
            array_push($missingCommands, 'whoami');
        }
        
        return $missingCommands;
    }

    /**
     * Returns username webserver (eg. apache) is using.
     */
    function getCurrentUser(){
        return shell_exec('whoami');
    }
    
    /**
     * Returns current crontab file
     */
    function getCrontab(){
        return shell_exec('crontab -l');
    }
    
    /**
     * Saves crontab file
     */
    function saveCrontab($newCrontab){
        $filename = sprintf('/tmp/systemcron.%s.txt', time());
        $newCrontab = str_replace(chr(13), '', $newCrontab);
        file_put_contents($filename, $newCrontab);
        $output = shell_exec(sprintf('crontab %s 2>&1', $filename));
        unlink($filename);
        
        if(strpos($output,':') !== false){
            $explodedOutput = explode(':', $output);
            return end($explodedOutput);
        }
        return true;
    }
    
    /**
     * Appends given job to crontab
     */
    function addNewJob($job, $module){
        if(get_class($job) === 'CronJob'){
            $crontab = $this->getCrontab();
            $modulestart = sprintf('#CMSMSModule:%s', $module);
            $moduleend = sprintf('#ENDOF-CMSMSModule:%s', $module);            
            
        $crontab = str_replace(chr(13), '', $crontab);
            $count = 0;
            $newline = $modulestart . '$1'. $job . chr(10) . $moduleend;
            $crontab = preg_replace(sprintf('/%s(.*)%s/sm', $modulestart, $moduleend), $newline, $crontab, -1, $count);
            if($count < 1){
                $crontab .= chr(10) . $modulestart . chr(10);
                $crontab .= $job . chr(10);
                $crontab .= $moduleend . chr(10);
            }
            return $this->saveCrontab(trim($crontab));
        }
    }
    /**
     * Removes given job from crontab
     */
    private $_tempJob;
    function removeJob($job, $module){
        if(get_class($job) === 'CronJob'){
            $crontab = $this->getCrontab();
            $modulestart = sprintf('#CMSMSModule:%s', $module);
            $moduleend = sprintf('#ENDOF-CMSMSModule:%s', $module);            
            
            $crontab = str_replace(chr(13), '', $crontab);
            $count = 0;
            $this->_tempJob = $job;
            $crontab = preg_replace_callback(sprintf('/%s(.*)%s/sm', $modulestart, $moduleend), array($this, 'removeFromCrontab'), $crontab, -1, $count);
            if($count < 1){
                $crontab .= chr(10) . $modulestart . chr(10);
                $crontab .= $job . chr(10);
                $crontab .= $moduleend . chr(10);
            }
            return $this->saveCrontab(trim($crontab));
        }
    }
    
    /**
     * Remove all jobs from given module
     */
    function removeAllJobs($module){
        if(is_string($module)){
            $crontab = $this->getCrontab();
            $modulestart = sprintf('#CMSMSModule:%s', $module);
            $moduleend = sprintf('#ENDOF-CMSMSModule:%s', $module);            
            
            $crontab = str_replace(chr(13), '', $crontab);
            $count = 0;
            $this->_tempJob = $job;
            $crontab = preg_replace_callback(sprintf('/%s(.*)%s/sm', $modulestart, $moduleend), array($this, 'returnEmpty'), $crontab, -1, $count);
            //$crontab = preg_replace(sprintf('/%s(.*)%s/sm', $modulestart, $moduleend), '$1', $crontab, -1, $count);
            if($count < 1){
                $crontab .= chr(10) . $modulestart . chr(10);
                $crontab .= $job . chr(10);
                $crontab .= $moduleend . chr(10);
            }
            return $this->saveCrontab(trim($crontab));
        }
    }
    private function removeFromCrontab($match){
        $jobs = trim(str_replace($this->_tempJob.chr(10), '', $match[1]));
        if(strlen($jobs) > 0){
            return str_replace($this->_tempJob.chr(10), '', $match[0]);
        } else {
            return '';
        }
    }
    private function returnEmpty($match){        
        return '';
    }
    
    /**
     * Test if command is found from system
     */
    private function testCommand($command){
        $cmdWhoami = shell_exec(sprintf('%s >/dev/null && echo true || echo false', $command));
        if($cmdWhoami == 'false'){
           return false;
        } else {
            return true;
        }
    }
    
    
}
?>
