<?php
/**
 * @class	pushnotificationAdminView
 * @author	Wincomi (https://www.wincomi.com)
 * @brief	Admin view class in the pushnotification module
 */

class pushnotificationAdminView extends pushnotification
{
	function init()
	{		
		$template_path = sprintf("%stpl/",$this->module_path);
		$this->setTemplatePath($template_path);
	}
	
	function dispPushnotificationAdminConfig()
	{
		$oModuleModel = getModel('module');
		
		$config = $oModuleModel->getModuleConfig('pushnotification');
		Context::set('config', $config);

		$oModuleModel = getModel('module');
		$skin_list = $oModuleModel->getSkins($this->module_path);
		Context::set('skin_list', $skin_list);

		$mskin_list = $oModuleModel->getSkins($this->module_path, "m.skins");
		Context::set('mskin_list', $mskin_list);

		$this->setTemplateFile('config');
	}

}
/* End of file pushnotification.admin.view.php */
/* Location: ./modules/pushnotification/pushnotification.admin.view.php */
