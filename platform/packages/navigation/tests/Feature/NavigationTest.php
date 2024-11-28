<?php

use Workable\Navigation\Enums\CategoryMultiEnum;
use Workable\Navigation\Models\Navigation;
use Workable\UserTenant\Tests\BaseAuthTest;

class NavigationTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = [
            'name'      => 'Nav Left',
            'url'       => 'api/v1/nav-left',
            'type'      => 'frontend',
            'icon'      => null,
            'view_data' => null,
            'label'     => 0,
            'layout'    => 0,
            'sort'      => 0,
            'is_auth'   => 0,
            'status'    => CategoryMultiEnum::STATUS_INACTIVE,
            'meta'      => [
                'charset'  => 'test Navigation',
                'content'  => 'test Navigation',
                'title'    => 'test Navigation',
                'viewport' => 'test Navigation',
            ]
        ];

        $this->item = Navigation::create([
            'name'       => 'Nav Right',
            'root_id'    => 0,
            'parent_id'  => 0,
            'url'        => 'api/v1/nav-right',
            'type'       => 'backend',
            'icon'       => null,
            'view_data'  => null,
            'label'      => 1,
            'layout'     => 1,
            'sort'       => 1,
            'is_auth'    => 1,
            'status'     => CategoryMultiEnum::STATUS_ACTIVE,
            'meta'       => json_encode([
                'charset'  => 'test Navigation Right',
                'content'  => 'test Navigation Right',
                'title'    => 'test Navigation Right',
                'viewport' => 'test Navigation Right',
            ]),
        ]);

        $this->formatData = [
            'name',
            'root_id',
            'parent_id',
            'url',
            'type',
            'icon',
            'view_data',
            'label',
            'layout',
            'sort',
            'is_auth',
            'status',
            'meta',
        ];

        $this->updateUrl = route('api.navigation.update', $this->item->id);
    }

    public function test_create()
    {
        $response = $this->postJson(route('api.navigation.store'), $this->data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "navigation" => $this->formatData
                ]
            ]);
    }

    public function test_update()
    {
        $response = $this->postJson($this->updateUrl, $this->data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "navigation" => $this->formatData
                ]
            ]);
    }

    public function test_delete()
    {
        $response = $this->deleteJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_show()
    {
        $response = $this->json("GET", $this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "navigation" => $this->formatData
                ]
            ]);
    }
    public function test_list()
    {
        $response = $this->json("GET", route('api.navigation.index'), [
            'with' => 'createdBy '
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "navigations" => [
                        '*' => $this->formatData
                    ]
                ]
            ]);
    }

    public function test_validate()
    {
        $this->testValidate(route('api.navigation.store'));
    }

    protected function testValidate($route)
    {
        $testCases = [
            [
                'data'           => [],
                'expectedErrors' =>
                    [
                        'name',
                        'url',
                    ]
            ],
            [
                'data'           => ['name' => 'Test Navigation'],
                'expectedErrors' =>
                    [
                        'url',
                    ]
            ],

            [
                'data'           => ['url' => 'api/v1/nav-right'],
                'expectedErrors' =>
                    [
                        'name',
                    ]
            ],
            [
                'data'           => [
                    'name'      => 1,
                    'root_id'   => 'a',
                    'parent_id' => 'a',
                    'url'       => 1,
                    'type'      => 1,
                    'icon'      => 1,
                    'view_data' => 1,
                    'label'     => 'a',
                    'layout'    => 'a',
                    'sort'      => 'a',
                    'is_auth'   => 'a',
                    'status'    => 'a',
                    'meta'      => 'a',
                ],
                'expectedErrors' =>
                    [
                        'name',
                        'root_id',
                        'parent_id',
                        'url',
                        'type',
                        'icon',
                        'view_data',
                        'label',
                        'layout',
                        'sort',
                        'is_auth',
                        'status',
                        'meta',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", $route, $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
