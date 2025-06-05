<?php

namespace App\Infrastructure\Empresa\Models;

use App\Domain\Empresa\ValueObjects\Estado;
use App\Domain\Empresa\ValueObjects\Nit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmpresaModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'empresas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'telefono',
        'estado',
    ];

    protected $casts = [
        'nit' => Nit::class, 
        'estado' => Estado::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
