<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountProfilePicture extends APIModel
{
		protected $table = 'account_profile_pictures';
		protected $fillable = ['account_id', 'source'];
    public function account(){
      return $this->belongsTo('App\Account', 'account_id');
    }
}
