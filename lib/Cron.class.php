<?php
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
            //couldn't get callback working, if you get, please inform me!
            //$crontab = preg_replace_callback(sprintf('/%s(.*)%s/sm', $modulestart, $moduleend), array($this, 'modifyCrontab'), $crontab, $count);
            $newline = $modulestart . '$1'. $job . chr(10) . $moduleend;
            $crontab = preg_replace(sprintf('/%s(.*)%s/sm', $modulestart, $moduleend), $newline, $crontab, -1, $count);
            if($count < 1){
                $crontab .= chr(10) . $modulestart . chr(10);
                $crontab .= $job . chr(10);
                $crontab .= $moduleend . chr(10);
            }
            
            return $this->saveCrontab($crontab);
        }
    }
    public function modifyCrontab($match){
        return "moi";
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
