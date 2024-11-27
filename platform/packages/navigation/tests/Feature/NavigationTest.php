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
            'root_id'   => 0,
            'parent_id' => 0,
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
}
