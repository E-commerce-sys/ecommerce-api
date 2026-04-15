<?php
use Illuminate\Support\Facades\Storage;

$files = Storage::disk('s3')->allFiles('all-images/product-images');
print_r($files);