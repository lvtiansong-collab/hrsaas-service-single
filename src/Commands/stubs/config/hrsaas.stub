<?php

return [
    // RabbitMQ 队列配置
    'rabbitmq_queue' => env('RABBITMQ_QUEUE', ''),
    'rabbitmq_queue_error' => env('RABBITMQ_QUEUE_ERROR', ''),
    'rabbitmq_host' => env('RABBITMQ_HOST', '127.0.0.1'),
    'rabbitmq_port' => env('RABBITMQ_PORT', '5672'),
    'rabbitmq_vhost' => env('RABBITMQ_VHOST', '/'),
    'rabbitmq_login' => env('RABBITMQ_LOGIN', 'guest'),
    'rabbitmq_password' => env('RABBITMQ_PASSWORD', 'guest'),

    // Exchange 前缀
    'exchange_prefix' => 'Hrsaas.Message.Events:',

    // 模型命名空间配置
    'models_namespace' => 'App\Models',

    // 需要日期格式化的字段
    'date_columns' => [
        'created_date', 'last_modified_date', 'hired_date',
        'dob', 'internship_start_date', 'internship_end_date', 'terminated_date',
    ],

    // staff表字段映射设置 'employees源字段' => 'staffs目标字段'
    'staff' => [
        // 基本信息
        'id'                        => 'id',
        'user_id'                   => 'user_id',
        'number'                    => 'serial_number',
        'name'                      => 'name_cn_long',
        'gender'                    => 'gender',
        'birthday'                  => 'dob',
        'nationality'               => 'nation',
        'identification_number'     => 'identity_card_number',
        'country_code'              => 'country_code',

        // 组织关系
        'company_id'                => 'company_id',
        'department_id'             => 'department_id',
        'position_id'               => 'position_id',
        'supervisor_id'             => 'superior_id',
        'position_status'           => 'position_status',

        // 联系方式
        'email'                     => 'email',
        'phone_number'              => 'phone_number',
        'emergency_contact'         => 'emergency_contact',
        'emergency_contact_number'  => 'emergency_contact_phone_number',

        // 入职/离职
        'hire_date'                 => 'hired_date',
        'termination_date'          => 'terminated_date',
        'contract_status'           => 'contract_status',
        'internship_start_date'     => 'internship_start_date',
        'internship_end_date'       => 'internship_end_date',

        // 学历/婚姻
        'graduated_from'            => 'graduation_school',
        'major'                     => 'major',
        'education_level'           => 'education',
        'political_status'          => 'political_status',
        'marital_status'            => 'marital_status',
        'fertility_status'          => 'fertility_status',

        // 设备/门禁
        'fingerprint_number'        => 'finger_print_number',
        'entrance_guard_number'     => 'entrance_guard_number',
        'seat_number'               => 'seat_number',

        // 时间戳
        'created_at'                => 'created_date',
        'updated_at'                => 'last_modified_date',
    ],

    // unit表字断映射设置 'field' => '表对应字段名'
    'unit' => [
        "id" => "id",
        "name" => "unit_name",
        "type" => "type",
        "parent_id" => "parent_id",
        "parents_id" => "parents_id",
        "active" => "is_active",
        "country_code" => "country_code"
    ],

    // position表字断映射设置 'field' => '表对应字段名'
    'position' => [
        "id" => "id",
        "name" => "name",
        "organization_id" => "unit_id",
        "active" => "is_active",
    ],

    // Event 别名绑定配置
    "events" => [
        // 初始化事件
        "EmployeesPagingInitializationEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\EmployeesPagingInitializationEvent::class,
        "OrganizationsPagingInitializationEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationsPagingInitializationEvent::class,
        "PositionsPagingInitializationEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\PositionsPagingInitializationEvent::class,

        // 员工事件
        "EmployeeAddedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\EmployeeAddedEvent::class,
        "EmployeeUpdatedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\EmployeeUpdatedEvent::class,
        "EmployeeDeletedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\EmployeeDeletedEvent::class,

        // 组织事件
        "OrganizationAddedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationAddedEvent::class,
        "OrganizationUpdatedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationUpdatedEvent::class,
        "OrganizationDeletedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationDeletedEvent::class,
        "OrganizationMovedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationMovedEvent::class,

        // 职位事件
        "PositionAddedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\PositionAddedEvent::class,
        "PositionUpdatedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\PositionUpdatedEvent::class,
        "PositionDeletedEvent" => \Wiltechsteam\HrsaasServiceSingle\Events\PositionDeletedEvent::class,
    ],

    // Event 监听绑定
    "listens" => [
        // 初始化事件
        \Wiltechsteam\HrsaasServiceSingle\Events\EmployeesPagingInitializationEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\EmployeesPagingInitializationEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationsPagingInitializationEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\OrganizationsPagingInitializationEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\PositionsPagingInitializationEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\PositionsPagingInitializationEventListener::class,
        ],

        // 员工事件
        \Wiltechsteam\HrsaasServiceSingle\Events\EmployeeAddedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\EmployeeAddedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\EmployeeUpdatedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\EmployeeUpdatedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\EmployeeDeletedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\EmployeeDeletedEventListener::class,
        ],

        // 组织事件
        \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationAddedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\OrganizationAddedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationUpdatedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\OrganizationUpdatedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationDeletedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\OrganizationDeletedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\OrganizationMovedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\OrganizationMovedEventListener::class,
        ],

        // 职位事件
        \Wiltechsteam\HrsaasServiceSingle\Events\PositionAddedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\PositionAddedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\PositionUpdatedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\PositionUpdatedEventListener::class,
        ],
        \Wiltechsteam\HrsaasServiceSingle\Events\PositionDeletedEvent::class => [
            \Wiltechsteam\HrsaasServiceSingle\Listeners\PositionDeletedEventListener::class,
        ],
    ],
];
