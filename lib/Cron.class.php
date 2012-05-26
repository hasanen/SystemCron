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
    
    function saveCrontab($newCrontab){
        $filename = sprintf('/tmp/systemcron.%s.txt', time());
        file_put_contents($filename, $newCrontab.PHP_EOL);
        $output = shell_exec(sprintf('crontab %s 2>&1', $filename));
        unlink($filename);
        
        if(strpos($output,':') !== false){
            $explodedOutput = explode(':', $output);
            return end($explodedOutput);
        }
        return true;
    }
    
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
