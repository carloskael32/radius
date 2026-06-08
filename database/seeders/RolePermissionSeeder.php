<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        $adminRole = Role::create(["name" => "Administrador", "description" => "Acceso completo a todas las funciones del sistema"]);
        $OperadorRole = Role::create(["name" => "Operador", "description" => "Puede gestionar NAS, Clientes, Reportes, Planes"]);
        $ConsultaRole = Role::create(["name" => "Usuario de Consulta", "description" => "Puede Gestionar los Reportes y Visualizar"]);


        //Permisos  para NAS
        $permisoIndexNAS = Permission::create(["name" => "ver nas"]);
        $permisocreateNAS = Permission::create(["name" => "crear nas"]);
        $permisoeditNAS = Permission::create(["name" => "modificar nas"]);
        $permisodeleteNAS = Permission::create(["name" => "eliminar nas"]);


        // Permisos para Clientes
        $permisoIndexCli = Permission::create(["name" => "ver clientes"]);
        $permisocreateCli = Permission::create(["name" => "crear clientes"]);
        $permisoeditCli = Permission::create(["name" => "modificar clientes"]);
        $permisodeleteCli = Permission::create(["name" => "eliminar clientes"]);


        //Permiso para Usuarios
        $permisoIndexUsr = Permission::create(["name" => "ver usuarios"]);
        $permisocreateUsr = Permission::create(["name" => "crear usuarios"]);
        $permisoeditUsr = Permission::create(["name" => "modificar usuarios"]);
        $permisodeleteUsr = Permission::create(["name" => "eliminar usuarios"]);


        //Permisos para Roles
        $permisoIndexRol = Permission::create(["name" => "ver roles"]);
        $permisocreateRol = Permission::create(["name" => "crear roles"]);
        $permisoeditRol = Permission::create(["name" => "modificar roles"]);
        $permisodeleteRol = Permission::create(["name" => "eliminar roles"]);

        //Permisos para Planes de Servicio
        $permisoIndexPlanServ = Permission::create(["name" => "ver planes de servicio"]);
        $permisocreatePlanServ = Permission::create(["name" => "crear planes de servicio"]);
        $permisoeditPlanServ = Permission::create(["name" => "modificar planes de servicio"]);
        $permisodeletePlanServ = Permission::create(["name" => "eliminar planes de servicio"]);
        $permisoGestionClientesPlanServ = Permission::create(["name" => "gestion de clientes en planes de servicio"]);

        //Permisos para Reportes
        $permisoIndexReportes = Permission::create(["name" => "ver reportes"]);
        $permisocreateReportes = Permission::create(["name" => "crear reportes"]);
        $permisoeditReportes = Permission::create(["name" => "modificar reportes"]);
        $permisodeleteReportes = Permission::create(["name" => "eliminar reportes"]);

        //Permisos para Auditoria

        $permisoIndexAuditoria = Permission::create(["name" => "ver auditoria"]);




        // asignar los permisos a los roles
        //rol administrador : todos los permisos
        
        $adminRole->givePermissionTo(Permission::all());

        //rol operador : permisos especificos
        $OperadorRole->givePermissionTo([
            //planes de servicio
            $permisoIndexPlanServ,
            $permisocreatePlanServ,
            $permisoeditPlanServ,
            $permisodeletePlanServ,
            $permisoGestionClientesPlanServ,
            //clientes
            $permisoIndexCli,
            $permisocreateCli,
            $permisoeditCli,
            $permisodeleteCli,
            //Reportes
            $permisoIndexReportes,
            $permisocreateReportes,
            $permisoeditReportes,
            $permisodeleteReportes,
        ]);

        // Usuario de consulta : permisos especificos
        $ConsultaRole->givePermissionTo([
            //Reportes
            $permisoIndexReportes,
            $permisocreateReportes,
            $permisoeditReportes,
            $permisodeleteReportes,
        ]);
    }
}