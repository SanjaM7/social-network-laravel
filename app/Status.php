<?php

namespace Chatty;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'parent_id'
    ];

    public function profile()
    {
        return $this->belongsTo('Chatty\Profile');
    }

    public function scopeNotReply($query)
    {
        return $query->whereNull('parent_id');
    }

    public function replies()
    {
        return $this->hasMany('Chatty\Status', 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany('Chatty\Like', 'status_id');
    }
}
