<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category',
        'availability'
    ];

    public static $availability   = [
        'Available'  => "Available",
        'Issued'     => "Issued"
    ];
    public function issues()
    {
        return $this->hasMany(BookIssue::class, 'book_id');
    }
}
