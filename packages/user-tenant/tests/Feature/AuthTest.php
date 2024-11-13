<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $tenantData = [];
    protected $tenant = [];
    protected $storeUrl = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed', ['--class' => 'Workable\\UserTenant\\Database\\Seeders\\UserSeeder']);

    }

    public function test_login()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'username' => 'thuannn',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1,
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        "id",
                        "username",
                        "email",
                    ],
                    'token'
                ]
            ]);
    }

    public function test_login_failed()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'username' => 'thuannn',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonFragment([
            "code" => -1,
            'data' => []
        ]);
    }

    public function test_login_failed_validate()
    {
        $testCases = [
            [
                'data'           => [],
                'expectedErrors' =>
                    [
                        'username', 'password',
                    ]
            ],
            [
                'data'           => ['username' => 'thuannn'],
                'expectedErrors' =>
                    [
                        'password',
                    ]
            ],
            [
                'data'           => ['password' => 'password123'],
                'expectedErrors' =>
                    [
                        'username'
                    ]
            ]
        ];

        foreach ($testCases as $testCase) {
            $response = $this->postJson(route('api.auth.login'), $testCase['data']);

            $response->assertStatus(422)
                ->assertJsonValidationErrors($testCase['expectedErrors']);
        }
    }

    public function test_register()
    {
        $data     = [
            'username'              => 'thuannn01',
            'email'                 => 'thuannn01@gmail.com',
            'password'              => 'password',
            'phone'                 => '0223456789',
            'address'               => 'Số 9 tôn thất thuyết, cầu giấy, hà nội',
            'password_confirmation' => 'password'
        ];
        $response = $this->postJson(route('api.auth.register'), $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1,
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        "id",
                        "username",
                        "email",
                    ],
                    'token'
                ]
            ]);
    }

    public function test_register_failed_validate()
    {
        $testCases = [
            [
                'data'           => [],
                'expectedErrors' =>
                    [
                        'username',
                        'email',
                        'password',
                        'phone',
                        'address',
                    ]
            ],
            [
                'data'           => ['username' => 'thuannn01'],
                'expectedErrors' =>
                    [
                        'email',
                        'password',
                        'phone',
                        'address',
                    ]
            ],
            [
                'data'           => [
                    'email' => 'thuannn01@gmail.com',
                ],
                'expectedErrors' =>
                    [
                        'username',
                        'password',
                        'phone',
                        'address',
                    ]
            ],

            [
                'data'           => [
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ],
                'expectedErrors' =>
                    [
                        'email',
                        'username',
                        'phone',
                        'address',
                    ]
            ],
            [
                'data'           => ['phone' => '0123456799'],
                'expectedErrors' =>
                    [
                        'username',
                        'email',
                        'password',
                        'address',
                    ]
            ],
            [
                'data'           => ['address' => 'Số 10 tôn thất thuyết, cầu giấy, hà nội'],
                'expectedErrors' =>
                    [
                        'username',
                        'email',
                        'password',
                        'phone',
                    ]
            ],

            // case sai du lieu
            [
                'data'           => [
                    'username' => 11,
                    'email' => 11,
                    'password' => 11,
                    'phone' => 11,
                    'address' => 11,
                    'password_confirmation' => 'password',
                ],
                'expectedErrors' =>
                    [
                        'username',
                        'email',
                        'password',
                        'phone',
                        'address',
                    ]
            ],

        ];
        foreach ($testCases as $testCase) {
            $response = $this->postJson(route('api.auth.register'), $testCase['data']);

            $response->assertStatus(422)
                ->assertJsonValidationErrors($testCase['expectedErrors']);
        }
    }
}
