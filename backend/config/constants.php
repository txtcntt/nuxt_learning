<?php

return [
    'item_per_page' => 10,
    'group_roles' => [
        'admin' => 'Admin',
        'editor' => 'Editor',
        'reviewer' => 'Reviewer',
    ],
    'user_active_status' => [
        0 => 'Tạm ngừng',
        1 => 'Đang hoạt động',
    ],
    'user_delete_status' => [
        'AVAILABLE' => 0,
        'DELETED' => 1,
    ],
    'product_status' => [
        0 => 'Ngừng bán',
        1 => 'Đang bán',
        2 => 'Hết hàng'
    ],
    'upload_image' => [
        'type' => '.png,.jpg,.jpeg',
        'size' => 2 * 1024 * 1024, //2Mb
        'width' => 1024, //1024px
    ],
    'product_id_prefix' => 'S',
    'product_id_length' => 10,
    'folder_path' =>[
        'root' => 'storage',
        'product' => 'products',
    ],
];