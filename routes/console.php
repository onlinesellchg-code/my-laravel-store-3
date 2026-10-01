<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('store:about', function () {
    $this->info('فروشگاه من آماده مدیریت است.');
})->purpose('Show store status');
