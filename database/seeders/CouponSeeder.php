<?php
namespace Database\Seeders;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
class CouponSeeder extends Seeder { public function run(): void { Coupon::firstOrCreate(['code'=>'WELCOME10'],['type'=>'percent','value'=>10,'usage_limit'=>100,'used_count'=>0,'expires_at'=>now()->addYear(),'is_active'=>true]); Coupon::firstOrCreate(['code'=>'OFF500'],['type'=>'fixed','value'=>500000,'usage_limit'=>50,'used_count'=>0,'expires_at'=>now()->addMonths(6),'is_active'=>true]); } }
