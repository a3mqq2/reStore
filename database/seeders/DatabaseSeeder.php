<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\City;
use App\Models\Content;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\User;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'مدير النظام',
        'email' => 'admin@demo.com',
            'password' => Hash::make(123123123),
            'phone' => "0900000000",
        ]);

        // Permissions
        Permission::create(['name' => 'users', "ar_name" => "المستخدمين"]);
        Permission::create(['name' => 'payment_methods', "ar_name" => " طرق الدفع  "]);
        Permission::create(['name' => 'coupons', "ar_name" => " الكوبونات   "]);
        Permission::create(['name' => 'discounts', "ar_name" => " التخفيضات   "]);
        Permission::create(['name' => 'content', "ar_name" => " محتوى الموقع   "]);
        Permission::create(['name' => 'reports', "ar_name" => "التقارير  "]);
        Permission::create(['name' => 'orders', "ar_name" => "الطلبات  "]);
        Permission::create(['name' => 'cities', "ar_name" => "المدن"]);
        Permission::create(['name' => 'customers', "ar_name" => "الزبائن"]);
        Permission::create(['name' => 'products', "ar_name" => "المنتجات"]);
        Permission::create(['name' => 'categories', "ar_name" => "الاقسام"]);

        $user = User::find(1);
        $permissions = ['users', 'cities','customers', 'products','categories','payment_methods','coupons', 'discounts','content','reports','orders'];
        $user->givePermissionTo($permissions);



        Category::create(['name' => "الشحن عن طريق ID"]);
        Category::create(['name' => "أكواد الالعاب"]);

        $libyan_cities = array(
            "طرابلس",
            "بنغازي",
            "مصراتة",
            "البيضاء",
            "سبها",
            "درنة",
            "الزاوية",
            "المرج",
            "طبرق",
            "اجدابيا",
            "زليتن",
            "سرت",
            "الكفرة",
            "غدامس",
            "بني وليد",
            "نالوت",
            "غريان",
            "الخمس",
            "ترهونة",
            "يفرن",
            "مزدة",
            "سلوق",
            "جالو",
            "اوباري",
            "هون",
            "القطرون",
            "الريانية",
            "صبراتة",
            "زوارة",
            "مرزق",
            "شحات",
            "تاورغاء",
            "تاجوراء",
            "السواني",
            "الرجبان",
            "براك الشاطئ",
            "القبة",
            "سلوق",
            "راس لانوف",
            "البريقة"
        );

        foreach($libyan_cities as $libyan_city) {
            $city = new City();
            $city->name = $libyan_city;
            $city->save();
        }


        Currency::create(['name' => "دينار ليبي", "symbol" => "د.ل"]);
        Currency::create(['name' => "دولار امريكي", "symbol" => "$"]);
        Currency::create(['name' => "يورو", "symbol" => "€"]);

        Content::create();
        PaymentMethod::create(['name' => "محفظتي", 'image' => 'wallet.svg','currency_id' => 1]);
    }
}
