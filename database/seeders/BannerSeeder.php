<?php
namespace Database\Seeders;
use App\Models\Banner;
use Illuminate\Database\Seeder;
class BannerSeeder extends Seeder { public function run(): void { if(Banner::count()>0)return; Banner::create(['title'=>'خرید آسان، سریع و مطمئن','subtitle'=>'محصولات متنوع با قیمت مناسب و ارسال سریع','button_text'=>'مشاهده محصولات','button_url'=>'/shop','image_url'=>null,'is_active'=>true,'sort_order'=>1]); Banner::create(['title'=>'تخفیف‌های ویژه شروع فروشگاه','subtitle'=>'چند محصول نمونه با قیمت ویژه برای شروع','button_text'=>'پیشنهادها','button_url'=>'/shop?q=','image_url'=>null,'is_active'=>true,'sort_order'=>2]); } }
