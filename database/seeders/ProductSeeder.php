<?php
namespace Database\Seeders;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
class ProductSeeder extends Seeder { public function run(): void { if(Product::count()>0) return; $data=[
['هدفون بی‌سیم مدل Pro X','wireless-headphone-pro-x','دیجیتال','🎧',2450000,2890000,15,true],
['ساعت هوشمند سری 5','smart-watch-series-5','دیجیتال','⌚',3890000,4250000,10,true],
['اسپیکر قابل حمل','portable-speaker','دیجیتال','🔊',1590000,null,9,true],
['چراغ مطالعه LED','led-study-lamp','خانه و آشپزخانه','💡',540000,null,12,false],
['ست ابزار 32 پارچه','tool-set-32','ابزار و تجهیزات','🧰',1790000,null,8,true],
['کوله‌پشتی روزمره','daily-backpack','ورزش و سفر','🎒',1290000,1490000,14,true],
['قمقمه استیل ورزشی','sport-steel-bottle','ورزش و سفر','🥤',690000,790000,20,false],
['تیشرت نخی ساده','simple-cotton-tshirt','پوشاک','👕',490000,590000,25,false],
['کرم مراقبت از پوست','skin-care-cream','زیبایی و سلامت','🧴',420000,480000,18,false],
['جاروبرقی خانگی','home-vacuum','خانه و آشپزخانه','🧹',7200000,7900000,6,true],
]; foreach($data as $r){$c=Category::where('name',$r[2])->firstOrFail();Product::create(['category_id'=>$c->id,'name'=>$r[0],'slug'=>$r[1],'sku'=>strtoupper(substr($r[1],0,6)).'-'.random_int(1000,9999),'short_description'=>'محصول نمونه برای شروع فروشگاه.','description'=>'این محصول نمونه برای راه‌اندازی اولیه فروشگاه ساخته شده است. می‌توانید مشخصات، قیمت، موجودی و اطلاعات آن را از پنل مدیریت ویرایش کنید.','emoji'=>$r[3],'price'=>$r[4],'old_price'=>$r[5],'stock'=>$r[6],'is_active'=>true,'is_featured'=>$r[7]]); } } }
