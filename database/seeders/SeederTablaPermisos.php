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

            //Tabla Operacion y Mantenimiento
            'ver-servicio_operacion_mantenimiento',
            'crear-servicio_operacion_mantenimiento',
            'borrar-servicio_operacion_mantenimiento',
                //PARTE DE EXPEDIENTE 
            'Generar-expediente-operacion',
            'Descargar-documentos-expediente-operacion',
                //PARTE DE DOCUMENTACION
            'Generar-documentacion-operacion',
            'Descargar-documentacion-operacion',   
                //PARTE DE COTIZACION
            'Descargar-cotizacion-operacion',
            'Generar-cotizacion-operacion',
                //PARTE DE PAGO
            'Ver-pagos',
            'Subir-pago-operacion',
            'Descargar-pago-operacion',
                //PARTE DE FACTURA
            'Subir-factura-operacion',
            'Descargar-factura-operacion',





            //Tabla Servicios Anexo 30
            'ver-servicio_anexo_30',
            'crear-servicio_anexo_30',           
            'borrar-servicio_anexo_30',
                //PARTE DE EXPEDIENTE
            'Generar-expediente-anexo_30',
            'Descargar-documentos-expediente-anexo_30',
                //PARTE DE DOCUMENTACION
            'Generar-documentacion-anexo_30',
                //PARTE DE COTIZACION
            'Generar-cotizacion-anexo_30', 
            'Descargar-cotizacion-anexo_30',
                //PARTE DE PAGO
            'Ver-pagos-anexo_30',
            'Subir-pago-anexo_30',
            'Descargar-pago-anexo_30',
                //PARTE DE FACTURA
            'Subir-factura-anexo_30',
            'Descargar-factura-anexo_30',

                //LISTA DE INSPECCION

                //DICTAMENES
            'Generar-dictamenes-anexo',
        

            //Tabla Formatos Vigentes
            'ver-formato_vigentes',
            'crear-formato_vigentes',
            'editar-formato_vigentes',
            'borrar-formato_vigentes',

            //Tabla Formatos Historial
            'ver-formato_historial',
            'crear-formato_historial',
            'editar-formato_historial',
            'borrar-formato_historial',


        ];
        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }
    }
}
