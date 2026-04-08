<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookIssue extends Model
{
    protected $fillable = [
        'book_id',
        'student_id',
        'issue_date',
        'return_date'
    ];

    public function student(){
        return $this->hasOne(SchoolStudent::class,'id','student_id');
    }

    public function book(){
        return $this->hasOne(LibraryBook::class,'id','book_id');
    }
}
