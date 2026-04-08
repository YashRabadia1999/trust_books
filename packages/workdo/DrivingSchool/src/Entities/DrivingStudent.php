<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'email',
        'password',
        'gender',
        'dob',
        'address',
        'city',
        'state',
        'country',
        'pin_code',
        'language',
        'mobile_no',
        'workspace',
        'created_by',
    ];

    public static function flagOfCountry()
    {
        $arr = [
            'ar' => '🇦🇪 ar',
            "zh" => "🇨🇳 zh",
            'da' => '🇩🇰 ad',
            'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
            'it' => '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'he' => '🇮🇱 he',
            'nl' => '🇳🇱 nl',
            'pl' => '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇧🇷 pt-br',
        ];
        return $arr;
    }
    protected static function newFactory()
    {
        return \Workdo\DrivingSchool\Database\factories\DrivingStudentFactory::new();
    }
}


