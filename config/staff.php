<?php

return [
    // عدد التعديلات المسموح به كافتراضي عند إنشاء سجل جديد
    'edits_allowed_default' => env('STAFF_EDITS_ALLOWED_DEFAULT', 1),

    // مدة صلاحية جلسة التحقق (بالدقائق)
    'edit_session_ttl' => env('STAFF_EDIT_SESSION_TTL', 15),
];
