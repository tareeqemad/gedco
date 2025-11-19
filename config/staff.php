<?php

return [

    'edits_allowed_default' => env('STAFF_EDITS_ALLOWED_DEFAULT', 1),


    'edit_session_ttl' => env('STAFF_EDIT_SESSION_TTL', 15),


    'employee_lookup_api_url' => env('STAFF_EMPLOYEE_LOOKUP_API_URL', 'https://eservices.gedco.ps/api/employees/search'),
];
