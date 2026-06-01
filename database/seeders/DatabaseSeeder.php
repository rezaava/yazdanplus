<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role=new Role;
        $role->name='admin';
        $role->save();
        $role=new Role;
        $role->name='user';
        $role->save();

        $user=new User;
        $user->name='n1';
        $user->family='f1';
        $user->password=bcrypt(1);
        $user->mobile='1';
        $user->save();
        $role2=Role::where('name','=','admin')->first();
        $user->addRole($role2);
        
        for($i=1;$i<21;$i++){
            $cat=new Category;
            $cat->name=$i;
            $cat->save();

             $product=new Product();
             $product->name=$i;
             $product->category_id=$i;
             $product->price=$i;
             $product->save();
        }
    }
}
