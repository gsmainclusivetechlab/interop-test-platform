<?php

return [
    'compliance_session_execution_limit' =>
        env('COMPLIANCE_SESSION_EXECUTION_LIMIT') ?: 5,

    'available_modes' => [
        'test' => env('SESSION_TEST_AVAILABLE', true),
        'test_questionnaire' => env('SESSION_TEST_QUESTIONNAIRE_AVAILABLE', true),
        'compliance' => env('SESSION_COMPLIANCE_AVAILABLE', true)
    ]
];
