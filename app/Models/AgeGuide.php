<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'age_label', 'age_min_weeks', 'age_max_weeks',
    'weekly_goals', 'feeding_tips', 'sleep_tips',
    'development_tips', 'safety_tips',
])]
class AgeGuide extends Model
{
    //
}
