<?php
namespace Database\Seeders;
use App\Models\Setting;
use Illuminate\Database\Seeder;
class SettingSeeder extends Seeder { public function run(): void { $values=['store_name'=>'فروشگاه من','store_phone'=>'۰۲۱-۱۲۳۴۵۶۷۸','store_address'=>'تهران، خیابان نمونه، پلاک ۱۰','shipping_cost'=>'120000','free_shipping_threshold'=>'5000000']; foreach($values as $k=>$v) Setting::firstOrCreate(['key'=>$k],['value'=>$v]); } }
