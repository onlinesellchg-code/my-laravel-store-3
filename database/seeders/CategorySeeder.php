<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;
class CategorySeeder extends Seeder { public function run(): void { if(Category::count()>0) return; $rows=[['دیجیتال','digital','💻'],['خانه و آشپزخانه','home','🏠'],['ابزار و تجهیزات','tools','🔧'],['پوشاک','fashion','👕'],['ورزش و سفر','sport','🎒'],['زیبایی و سلامت','beauty','✨']]; foreach($rows as $i=>$r) Category::create(['name'=>$r[0],'slug'=>$r[1],'icon'=>$r[2],'sort_order'=>$i+1,'is_active'=>true]); } }
