<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Mensas extends Eloquent
{
	protected $connection = 'mongodb';
}
