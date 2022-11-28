<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Root', 'description' => 'Aceso total a todo el sistema, sin restricción.'],
            ['id' => 2, 'name' => 'Directivo', 'description' => 'Aceso total a todo el sistema, sin restricción. A excepción de que no puede eliminar al usuario root.'],
            ['id' => 3, 'name' => 'Administrador', 'description' => 'Acceso al sistema excepto a la parte de tranferencias.'],
            ['id' => 4, 'name' => 'Subadministrador', 'description' => 'Solo acceso a la sección de tranferencias dadas por el administrador'],
        ];
        Role::insert($data);
    }
}
