<?php
namespace JDS\CoreMVC;
use JDS\CoreMVC\DB\DbModel;


abstract class UserModel extends DbModel {

	abstract public function getDisplayName(): string;

}