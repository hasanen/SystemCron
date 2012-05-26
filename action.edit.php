<?php

if (!isset($gCms)) exit;

if (! $this->CheckAccess())
	{
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
	}

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

   Code for systemcron "defaultadmin" admin action
   
   -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
   
   Typically, this will display something from a template
   or do some other task.
   
*/
$crontab_file = $this->GetCron()->getCrontab();
if(array_key_exists('cancelEditCrontab', $params)){
   $this->Redirect($id, 'defaultadmin');
}
if(array_key_exists('saveCrontab', $params)){
   $output = $this->getCron()->saveCrontab($params['crontab']);
   if($output === true){
      $this->Redirect($id, 'defaultadmin','', Array('module_message'=>$this->Lang('operation.succeed')));
   }else{
      echo $this->ShowErrors($output);
      $crontab_file =$params['crontab'];
   }
}

$this->smarty->assign('form_start', $this->CreateFormStart($id, 'edit', $returnid,'post'));
$this->smarty->assign('username', sprintf($this->Lang('msg.username'), $this->GetCron()->getCurrentUser()));
$this->smarty->assign('crontab', $this->CreateTextArea(false, $id, $crontab_file , 'crontab'));

$this->smarty->assign('btn_save', $this->CreateInputSubmit($id, 'saveCrontab', $this->lang('btn.save')));
$this->smarty->assign('btn_cancel', $this->CreateInputSubmit($id, 'cancelEditCrontab', $this->lang('btn.cancel')));
$this->smarty->assign('form_end', $this->CreateFormEnd());

echo $this->ProcessTemplate('edit.tpl');

?>
