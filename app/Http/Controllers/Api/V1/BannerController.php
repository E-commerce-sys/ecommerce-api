<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BannerResource;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index() {
        return BannerResource::collection(
            Banner::orderBy(config('app.main_order_by_field'))->get()
        );
    }
}
