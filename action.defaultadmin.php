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

    $this->smarty->assign('username', sprintf($this->Lang('msg.username'), $this->GetCron()->getCurrentUser()));
    $this->smarty->assign('crontab', $this->GetCron()->getCrontab());

    $this->smarty->assign('form_start', $this->CreateFormStart($id, 'edit', $returnid,'post'));
    $this->smarty->assign('btn_edit', $this->CreateInputSubmit($id, 'editCrontab', $this->lang('btn.edit')));
    $this->smarty->assign('form_end', $this->CreateFormEnd());

    echo $this->ProcessTemplate('adminpanel.tpl');


?>
