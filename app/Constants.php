<?php
const ROUTE_ADMIN_REDIRECT = '/admin';
const CLASS_NAME_ACTIVE = 'success';
const CLASS_NAME_INACTIVE = 'danger';
const STATUS_INACTIVE = 0;
const STATUS_ACTIVE = 1;
const PAGINATE_DEFAULT = 10;
const ARRAY_LIMIT = [10, 20, 30, 40, 50];
const ARRAY_STATUS = [STATUS_ACTIVE => 'Hoạt động', STATUS_INACTIVE => 'Ngừng hoạt động'];

const ARRAY_ORDER_BY_USER = [
    'name' => 'Tên',
    'email' => 'Email',
    'created_at' => 'Ngay tạo',
    'updated_at' => 'Ngày cập nhật',
    'address' => 'Địa chỉ',
    'number_phone' => 'Số điện thoại',
];
define("RAPID_API_TIKTOK", [
    'api_url' => 'https://tiktok-downloader-download-tiktok-videos-without-watermark.p.rapidapi.com/vid/index',
    'headers' => [
        'X-RapidAPI-Key' => env('RAPID_API_KEY', '70a786bf74msh0b40cda63d301e2p1863e5jsn5b1257b12287'),
        'X-RapidAPI-Host' => 'tiktok-downloader-download-tiktok-videos-without-watermark.p.rapidapi.com'
    ],
]);
define("RAPID_API_YOUTUBE", [
    'api_url' => 'https://youtube-video-download-info.p.rapidapi.com/dl',
    'headers' => [
        'X-RapidAPI-Key' => env('RAPID_API_KEY_TIKTOK', '83ac970334msh7beb386bd5da605p171318jsn72e3b1f5ec47'),
        'X-RapidAPI-Host' => 'youtube-video-download-info.p.rapidapi.com'
    ]
]);

