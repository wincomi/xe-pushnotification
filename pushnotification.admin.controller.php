<?php
/**
 * @class	pushnotificationAdminController
 * @author	Wincomi (https://www.wincomi.com)
 * @brief	Admin controller class in the pushnotification module
 */

class pushnotificationAdminController extends pushnotification
{
	function procPushnotificationAdminSaveConfig()
	{
		$module_config = Context::getRequestVars();
		getDestroyXeVars($module_config);
		unset($module_config->module);
		unset($module_config->act);
		unset($module_config->mid);
		unset($module_config->vid);

		if(!$module_config || !is_object($module_config))
		{
			$module_config = new stdClass();
		}

		$oModuleController = getController('module');
		$output = $oModuleController->updateModuleConfig('pushnotification', $module_config);
		if($output->toBool())
		{
			unset($this->module_config);
		}

		$success_return_url = Context::get('success_return_url');

		$this->setMessage('success_updated');
		$this->setRedirectUrl($success_return_url);
	}

}
/* End of file pushnotification.admin.controller.php */
/* Location: ./modules/pushnotification/pushnotification.admin.controller.php */
