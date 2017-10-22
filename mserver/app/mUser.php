<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class mUser extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'users';
}
