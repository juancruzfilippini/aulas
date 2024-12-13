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

    public static function getNameById($id)
{
    // Verifica si el ID es válido
    if (empty($id)) {
        return 'ID no proporcionado.';
    }

    // Consulta la base de datos (ajusta 'YourModel' y 'name' según tu modelo y columna)
    $name = Places::where('id', $id)->value('name');

    // Retorna el nombre o un mensaje si no se encuentra
    return $name ? $name : 'Nombre no encontrado para el ID proporcionado.';
}

}
