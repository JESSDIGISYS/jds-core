<?php

namespace App\Core\Exception;

use Exception;

class ForbiddenException extends Exception {
	protected $code = 403;
	protected $message = 'You don\'t have permission to access this page!';
}



