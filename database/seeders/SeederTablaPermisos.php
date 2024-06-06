<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//Spatie
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            //Tabla roles
            'crear-rol',
            'editar-rol',
            'borrar-rol',

            //Tabla Usuarios
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'borrar-usuarios',

            //Tabla Obras
            'ver-obras',
            'crear-obras',
            'editar-obras',
            'borrar-obras',

            //Tabla Operacion y Mantenimiento
            'ver-operacion',
            'crear-operacion',
            'editar-operacion',
            'borrar-operacion',

            //Tabla Archivos
            'ver-archivos',
            'crear-archivos',
            'editar-archivos',
            'borrar-archivos',

            //Tabla Diseño 
            'ver-diseño',
            'crear-diseño',
            'editar-diseño',
            'borrar-diseño',

            //Tabla Construccion
            'ver-construccion',
            'crear-construccion',
            'editar-construccion',
            'borrar-construccion',

            //Tabla Construccion
            'ver-planos',
            'crear-planos',
            'editar-planos',
            'borrar-planos',

            //Tabla Construccion
            'ver-anexo',
            'crear-anexo',
            'editar-anexo',
            'borrar-anexo',

            //Tabla Servicios
            'ver-servicio',
            'crear-servicio',
            'editar-servicio',
            'borrar-servicio',

            //Tabla Formatos
            'ver-formato',
            'crear-formato',
            'editar-formato',
            'borrar-formato',



        ];
        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }
    }
}
