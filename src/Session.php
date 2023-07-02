<?php

namespace JDS\CoreMVC;

class Session {
	protected const FLASH_MSG = 'flash_messages';
	public function __construct() {
		session_start();
		$flashMessages = $_SESSION[self::FLASH_MSG] ?? [];
		foreach ($flashMessages as $key => &$flashMessage) {
			// Mark to be removed
			$flashMessage['remove'] = true;
		}
		$_SESSION[self::FLASH_MSG] = $flashMessages;
	}

	public function setFlash($key, $message) {
		$_SESSION[self::FLASH_MSG][$key] = ['remove' => false, 'value' => $message];
	}

	public function getFlash($flash) {
		return $_SESSION[self::FLASH_MSG][$flash]['value'] ?? false;
	}

	public function __destruct() {
		$flashMessages = $_SESSION[self::FLASH_MSG] ?? [];
		foreach ($flashMessages as $key => &$flashMessage) {
			if ($flashMessage['remove']) {
				unset($flashMessages[$key]);
			}
		}
		$_SESSION[self::FLASH_MSG] = $flashMessages;
	}

	public function __set($property, $value) {
		$_SESSION[$property] = $value;
	}

	public function __get($property) {
		return $_SESSION[$property] ?? false;
	}

	public function remove($property) {
		if (isset($_SESSION[$property])) {
			unset($_SESSION[$property]);
		}
	}
}
