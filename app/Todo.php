<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\carbon;
use App\Collaborator;
use App\User;
class Todo extends Model
{
    protected $fillable = [

        'title',
        'task',
        'date_created',
        'completion_date'
    ];
    protected $dates = ['completion_date'];

    public function collaborators(){
        return $this->belongsToMany(Collaborator::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function setCompletionDateAttribute($date)
    {
        $this->attributes['completion_date'] = Carbon::parse($date);
    }
    public function getCompletionDateAttribute($date)
    {
        return Carbon::parse($date)->toFormattedDateString();
    }
    public function scopeSearch($query,$keyword)
    {
        return $query->where('title', 'like', '%' .$keyword. '%')
                ->orWhere('date_created', 'like', '%' .$keyword. '%')
                ->orWhere('completion_date', 'like', '%' .$keyword. '%');
    }
    public function scopeGetCompleted($query)
    {
        return $query->Select('*')
        ->where('user_id','=',auth()->user()->id)
                ->where('completion_date','<', Carbon::now()->toDateString());
    }
    public function scopeGetProcessing($query)
    {
        return $query->Select('*')
        ->where('user_id','=',auth()->user()->id)
                ->where('completion_date','=', Carbon::now()->toDateString());
    }
    public function scopeGetPending($query)
    {
        return $query->Select('*')
        ->where('user_id','=',auth()->user()->id)
                ->where('completion_date','>', Carbon::now()->toDateString());
    }
    
    public function labels(){   
        return $this->belongsToMany(Label::class);
    }
}
