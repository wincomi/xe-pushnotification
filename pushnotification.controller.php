<?php
/**
 * @class	pushnotificationController
 * @author	Wincomi (https://www.wincomi.com)
 * @brief	Controller class in the pushnotification module
 */

class pushnotificationController extends pushnotification
{
	/**
	 * @brief	푸시 알림 기기 등록
	 * @return	Object
	 */
	function procPushnotificationInsertDevice()
	{
		if(!Context::get('is_logged'))
		{
			return new Object(-1, 'msg_not_logged');
		}

		$logged_info = Context::get('logged_info');

		$args = Context::getRequestVars();
		$args->srl = getNextSequence();
		$args->member_srl = $logged_info->member_srl;
		$output = executeQuery("pushnotification.insertDevice", $args);

		return $output;
	}
	
	/**
	 * @brief	푸시 알림 기기 삭제
	 * @return	Object
	 */
	function procPushnotificationDeleteDevice()
	{
		if(!Context::get('srl'))
		{
			return new Object(-1, 'msg_invalid_request');
		}

		// TODO: 회원의 기기인지 확인하는 기능 추가. 아닐 경우 invalid_request
		
		$args = new stdClass();
		$args->srl = Context::get('srl');

		$output = executeQuery("pushnotification.deleteDevice", $args);
		
		return $output;
	}
	

	/**
	 * @param	$obj
	 * @return	Object
	 */
	function triggerModuleHandlerBefore($obj)
	{
		$oMemberController = getController('member');
		$oMemberController->addMemberMenu('dispPushnotificationManageDevice', 'pn_manage_device');			
	}
	
	/**
	 * @brief	알림센터 모듈 트리거
	 * @param	$obj
	 * @return	Object
	 */
	function triggerInsertNotifyAfter($obj)
	{
		if($config->use_ncenterlite == 'N')
		{
			return;
		}
		
 		$args = new stdClass();
 		
		$args->member_srl = $obj->member_srl;
		
		$device_id = executeQuery("pushnotification.getDeviceId", $args);

		if(!$device_id)
		{
			return;
		}

		$oNcenterliteModel = getModel('ncenterlite');
		$message = strip_tags($oNcenterliteModel->getNotificationText($obj));
		
		$data = array(
			'url' => $obj->target_url,
			'regdate' => $obj->regdate,
		);

		$this->sendPushnotification("dgKETBLr6cI:APA91bGG6iWIZZuMjXC9s0P0ab4ZGqfnFwG8JW-76MC4ij44pyBhG_j05NAhkYVhWyt5wU001pMMU0REzNsClJUdjM9bUs8FDpfa45EcOUbaK1fd_F0qzK9idOkv8vMGbwkLXRvotY_L", $message, $data);
	}

	/**
	 * @brief	FCM을 이용하여 푸시 알림을 전송합니다.
	 * @param	$device_id
	 * @param	$message
	 * @param	$data
	 * @return	Object
	 */
	function sendPushnotification($device_id, $message, $data)
	{
		$oModuleModel = getModel('module');		
		$module_config = $oModuleModel->getModuleConfig('pushnotification');

		if(!$module_config->firebase_server_key) 
		{
			return new Object();
		}

		$url = 'https://fcm.googleapis.com/fcm/send';

		$headers = array(
			'Authorization: key=' .$module_config->firebase_server_key,
			'Content-Type: application/json'
		);
		
		$fields = array(
			'notification' => array(
				// 'title' => $title,
				'body' => $message,
				'sound' => 'default',
			),
			'priority' => 'high',
		);
		
		if($data)
		{
			$fields['data'] = $data;
		}

		if(is_array($device_id))
		{
			$fields['registration_ids'] = $device_id;
		} else
		{
			$fields['to'] = $device_id;
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		$result = curl_exec($ch);           
		if($result === FALSE)
		{
			die(curl_error($ch));
		}
		
		curl_close($ch);
		
		return $result;
	}
	
	/**
	 * @brief	회원 번호로 기기 목록을 가져옵니다.
	 * @param	$member_srl
	 * @return	array
	 */
	/* function getDevice($member_srl)
	 {
 		$args = new stdClass();
		$args->member_srl = $member_srl;
		$output = executeQuery("pushnotification.getDeviceId", $args);
		return $output;
	 }
	*/
	
}

/* End of file pushnotification.controller.php */
/* Location: ./modules/pushnotification/pushnotification.controller.php */
