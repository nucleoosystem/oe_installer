<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2016
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2016, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */


namespace OEModule\Internalreferral\components;

/**
 * Class WinDipIntegration
 *
 * Integration component for WinDip referral - provides link data to WinDip based on module
 * configuration.
 *
 * In current implementation, is only example of 3rd party; aspects should be abstracted as and when
 * further integrations are created.
 *
 * @package OEModule\Internalreferral\components
 */
class WinDipIntegration extends \CApplicationComponent
{
	protected $yii;
	protected $required_params =  array(
		'launch_uri',
		'application_id',
		'hashing_function'
	);

	public $launch_uri;
	public $application_id;
	public $hashing_function;

	/**
	 * Template path for the WinDip request
	 *
	 * @var string
	 */
	protected $request_template = 'Internalreferral.views.windipintegration.request_xml';

	/**
	 * WinDipIntegration constructor.
	 *
	 * @param \CApplication|null $yii
	 * @param array $params
	 * @throws \Exception
	 */
	public function init()
	{
		if (is_null($this->yii))
			$this->yii = \Yii::app();

		foreach ($this->required_params as $p) {
			if (!isset($this->$p) || is_null($this->$p))
				throw new \Exception("Missing required parameter {$p}");
		}
	}

	/**
	 * Convenience wrapper to allow template rendering.
	 *
	 * @param $view
	 * @param array $parameters
	 * @return mixed
	 */
	protected function renderPartial($view, $parameters = array())
	{
		return $this->yii->controller->renderPartial($view, $parameters, true);
	}

	/**
	 * Build a request for the given event
	 *
	 * @param \Event $event
	 * @param \DateTime $when
	 * @param $message_id
	 * @return array
	 */
	protected function constructRequestData(\Event $event, \DateTime $when, $message_id)
	{
		//TODO: better way of handling mysql date to datetime
		$event_date = \DateTime::createFromFormat('Y-m-d H:i:s', $event->event_date);

		$indexes = array();
		$indexes[] = array(
			'id'=>'hosnum',
			'value'=> $event->episode->patient->hos_num
		);

		$user = \User::model()->findByPk(\Yii::app()->user->id);

		return array(
			'timestamp' => $when->format('Y-m-d H:i:s'),
			'message_id' => $message_id,
			'application_id' => $this->application_id,
			'username' => $user->username,
			'user_displayname' => $user->getReversedFullName(),
			'event_id' => $event->id,
			// TODO: make this dynamic
			'windip_type_id' => 1,
			'event_date' => $event_date->format('Y-m-d'),
			'event_time' => $event_date->format('H:i:s'),
			'additional_indexes' => $indexes
		);
	}

	/**
	 * Generate a unique ID for the event message to be sent to WinDip
	 *
	 * @TODO: determine if ID should be stored with the event and maintained for subsequent links
	 *
	 * @param \Event $event
	 * @return string
	 */
	protected function getMessageId(\Event $event)
	{
		return \Helper::generateUuid();
	}

	/**
	 * Generate the authentication hash for the WinDip request.
	 *
	 * @param $data
	 * @return mixed
	 * @throws \Exception
	 */
	private function generateAuthenticationHash($data)
	{
		if (!is_method($this, 'hashing_function')) {
			throw new \Exception("A hashing function must be provided to generate the authentication hash for the WinDip integration.");
		}

		return call_user_func($this->hashing_function, $data, $this->request_template);
	}

	/**
	 * @param \Event $event
	 * @return mixed
	 * @throws \Exception
	 */
	public function generateXmlRequest(\Event $event)
	{
		$when = new \DateTime();
		$message_id = $this->getMessageId($event);

		$data = $this->constructRequestData($event, $when, $message_id);

		$data['authentication_hash'] = $this->generateAuthenticationHash($data);

		return $this->renderPartial($this->request_template, $data, true);
	}
}