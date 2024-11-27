<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    use HasFactory;

    protected $connection = 'mysql'; // Para la base de datos 'db-sistema-ap'

    protected $table = 'places'; // Nombre de la tabla en la base de datos

    protected $fillable = ['name', 'capacity'];
}
