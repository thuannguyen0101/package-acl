<?php

use Workable\Navigation\Enums\CategoryMultiEnum;
use Workable\Navigation\Models\CategoryMulti;
use Workable\UserTenant\Tests\BaseAuthTest;

class CategoryMultiTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->item = CategoryMulti::create([
            'name'       => 'Laptop',
            'root_id'    => 0,
            'parent_id'  => 0,
            'url'        => 'api/v1/laptops',
            'type'       => 'news',
            'icon'       => null,
            'view_data'  => null,
            'label'      => 0,
            'layout'     => 0,
            'sort'       => 0,
            'is_auth'    => 0,
            'status'     => CategoryMultiEnum::STATUS_ACTIVE,
            'meta'       => json_encode([
                'charset'  => 'test category laptops',
                'content'  => 'test category laptops',
                'title'    => 'test category laptops',
                'viewport' => 'test category laptops',
            ]),
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id
        ]);

        $this->data = [
            'name'       => 'Sản phẩm',
            'url'        => 'api/v1/products',
            'type'       => 'default',
            'icon'       => null,
            'view_data'  => null,
            'label'      => 1,
            'layout'     => 1,
            'sort'       => 1,
            'is_auth'    => 1,
            'status'     => CategoryMultiEnum::STATUS_INACTIVE,
            'meta'       => [
                'charset'  => 'test category products',
                'content'  => 'test category products',
                'title'    => 'test category products',
                'viewport' => 'test category products',
            ],
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id
        ];

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

        $this->updateUrl = route('api.category_multi.update', $this->item->id);
    }

    public function test_create()
    {
        $response = $this->postJson(route('api.category_multi.store'), $this->data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "category_multi" => $this->formatData
                ]
            ]);
    }

    public function test_update()
    {
        $response = $this->postJson($this->updateUrl, $this->data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "category_multi" => $this->formatData
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
                    "category_multi" => $this->formatData
                ]
            ]);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.category_multi.index'), [
            'with' => 'createdBy '
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "category_multis" => [
                        '*' => $this->formatData
                    ]
                ]
            ]);
    }

    public function test_validate()
    {
        $this->testValidate(route('api.category_multi.store'));
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
                'data'           => ['name' => 'Test Category Multi'],
                'expectedErrors' =>
                    [
                        'url',
                    ]
            ],

            [
                'data'           => ['url' => 'api/v1/laptops'],
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
