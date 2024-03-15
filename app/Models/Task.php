<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, TaskUserManager;

    protected $guarded = [];

    /* We can also use these rules for validations and return the Json Response
    public static $rules = [
        'title'             =>  'required|string|max:255',
        'description'       =>  'nullable|string',
        'status'            =>  'required|string|in:pending,in_progress,completed',
        'deadline'          =>  'required|date',
        'assigned_user_id'   =>  ['required', Rule::exists('users', 'id')],
    ]; */
    
    public function formatDate()
    {
        return $this->getDates() ;
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }

    public function scopeFilter($query)
    {
        if(request('search')){
            $query
                ->where('deadline','like', '%'. request('search') .'%')
                ->orWhere('created_at','like', '%'. request('search') .'%')
                ->orWhere('title','like', '%'. request('search') .'%')
                ->orWhere('description','like', '%'. request('search') .'%');
        }

        if(request('searchbody')){
            $query
                ->orWhere('title','like', '%'. request('searchbody') .'%')
                ->orWhere('description','like', '%'. request('searchbody') .'%');
        }
    }
   

}
