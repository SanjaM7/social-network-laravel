<?php

namespace Chatty;

use Illuminate\Database\Eloquent\Model;

class Like extends Model

{
    public $timestamps = false;
    protected $fillable = [
        'profile_id'
    ];

    public function profile()
    {
        $this->belongsTo('Chatty\Profile', 'profile_id');
    }

    public function status()
    {
        $this->belongsTo('Chatty\Status', 'status_id');
    }
}
