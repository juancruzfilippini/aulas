<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $connection = 'mysql'; // Para la base de datos 'db-sistema-ap'

    protected $table = 'event'; // Nombre de la tabla en la base de datos

    protected $fillable = ['title', 'reason', 'place', 'date', 'start_time', 'end_time', 'requested_by', 'members'];
}
