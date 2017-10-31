<?php
/**
 * @class	pushnotificationView
 * @author	Wincomi (https://www.wincomi.com)
 * @brief	View class in the pushnotification module
 */

class pushnotificationView extends pushnotification
{
	/**
	 * @brief	Initilization
	 * @return	void
	 */
	function init()
	{
		$template_path = sprintf("%sskins/%s/", $this->module_path, $this->module_info->skin);
		if(!is_dir($template_path) || !$this->module_info->skin)
		{
			$template_path = sprintf("%sskins/%s/",$this->module_path, "default");
		}
		$this->setTemplatePath($template_path);	
	}

	/**
	 * @brief	회원별 기기 관리 페이지
	 */
	function dispPushnotificationManageDevice()
	{
		$logged_info = Context::get('logged_info');

		$args = new stdClass();
		$args->member_srl = $logged_info->member_srl;

		$output = executeQueryArray('pushnotification.getDevices', $args);

		Context::set('devices', $output->data);
		
		$this->setTemplateFile('manage_device');
	}
}

/* End of file pushnotification.view.php */
/* Location: ./modules/pushnotification/pushnotification.view.php */
