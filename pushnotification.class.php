<?php
/**
 * @class	pushnotification
 * @brief	Base class of pushnotification module
 * @author	Wincomi (https://www.wincomi.com)
 * @package /modules/pushnotification
 * @version 0.1
 */

class pushnotification extends ModuleObject
{
	private $triggers = array(
		array('ncenterlite._insertNotify', 'pushnotification', 'controller', 'triggerInsertNotifyAfter', 'after'),
		array('moduleHandler.init', 'pushnotification', 'controller', 'triggerModuleHandlerBefore', 'before'),
	);

	/**
	 * @return Object
	 */
	function moduleInstall()
	{
		return new Object();
	}

	/**
	 * @return bool
	 */
	function checkUpdate()
	{
		$oModuleModel = getModel('module');
		
		foreach($this->triggers as $trigger)
		{
			if(!$oModuleModel->getTrigger($trigger[0], $trigger[1], $trigger[2], $trigger[3], $trigger[4]))
			{
				return true;
			}
		}

		return false;
	}
	
	/**
	 * @return Object
	 */
	function moduleUpdate()
	{
		$oModuleModel = getModel('module');
		$oModuleController = getController('module');
		
		foreach($this->triggers as $trigger)
		{
			if(!$oModuleModel->getTrigger($trigger[0], $trigger[1], $trigger[2], $trigger[3], $trigger[4]))
			{
				$oModuleController->insertTrigger($trigger[0], $trigger[1], $trigger[2], $trigger[3], $trigger[4]);
			}
		}

		return new Object(0, 'success_updated');
	}
	
	/**
	 * @return Object
	 */
	function moduleUninstall()
	{
		$oModuleController = getController('module');

		foreach($this->triggers as $trigger)
		{
			$oModuleController->deleteTrigger($trigger[0], $trigger[1], $trigger[2], $trigger[3], $trigger[4]);
		}

		return new Object();
	}

}

/* End of file pushnotification.class.php */
/* Location: ./modules/pushnotification/pushnotification.class.php */
