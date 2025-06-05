<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Empresa\Models\EmpresaModel;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        EmpresaModel::create([
            'nit' => '900111222-1',
            'nombre' => 'TecnoSoluciones Activa SAS',
            'direccion' => 'Avenida Siempre Viva 742',
            'telefono' => '3012345678',
            'estado' => 'Activo',
        ]);

        EmpresaModel::create([
            'nit' => '900222333-2',
            'nombre' => 'Consultores Inactivos Ltda',
            'direccion' => 'Carrera 8 # 10 - 20',
            'telefono' => '3109876543',
            'estado' => 'Inactivo',
        ]);

        EmpresaModel::create([
            'nit' => '900333444-3',
            'nombre' => 'Servicios Globales Activos',
            'direccion' => 'Circular 75 # 30 - 05',
            'telefono' => '3001122334',
            'estado' => 'Activo',
        ]);

        EmpresaModel::create([
            'nit' => '800123456-7',
            'nombre' => 'Industrias Omega (Inactiva)',
            'direccion' => 'Zona Franca Bodega 5',
            'telefono' => '3158887766',
            'estado' => 'Inactivo',
        ]);

        EmpresaModel::create([
            'nit' => '800987654-3',
            'nombre' => 'Comercializadora El Sol Naciente',
            'direccion' => 'Calle Luna Calle Sol',
            'telefono' => '3047654321',
            'estado' => 'Activo',
        ]);


    }
}
