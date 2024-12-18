<?php

use Tests\BaseAuthTest;
use Workable\Budget\Enums\ExpenseCategoryEnum;
use Workable\Budget\Models\ExpenseCategory;

class ExpenseCategoryTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = [
            'name'        => 'Test ExpenseCategory',
            'description' => 'Test ExpenseCategory',
            'status'      => ExpenseCategoryEnum::STATUS_ACTIVE,
        ];

        $this->formatData   = [
            'id',
            'tenant_id',
            'name',
            'description',
            'status',
            'created_at',
        ];
        $this->expenseCategory = ExpenseCategory::create([
            'tenant_id'      => get_tenant_id(),
            'name'           => 'Test ExpenseCategory 02',
            'description'    => 'Test ExpenseCategory description 02',
            'status'         => ExpenseCategoryEnum::STATUS_ACTIVE,
            'area_id'        => 0,
            'area_source_id' => 0,
            'created_by'     => get_user_id(),
            'updated_by'     => get_user_id(),
        ]);

        $this->updateUrl = route('api.expense_category.update', $this->expenseCategory->id);
    }

    public function test_create()
    {
        $response = $this->postJson(route('api.expense_category.index'), $this->data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "expense_category" => $this->formatData
                ]
            ]);
    }

    public function test_show()
    {
        $response = $this->json('GET', $this->updateUrl, [
            'with' => 'tenant,createdBy,updatedBy'
        ]);

        $this->formatData = array_merge($this->formatData, [
            'tenant',
            'createdBy',
            'updatedBy',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "expense_category" => $this->formatData
                ]
            ]);
    }

    public function test_update()
    {
        $response = $this->json('POST', $this->updateUrl, [
            'name'        => 'Test Account Money 03',
            'description' => 'Test Account Money description 03',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "expense_category" => $this->formatData
                ]
            ]);
    }

    public function test_delete()
    {
        $response = $this->json('DELETE', $this->updateUrl);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_index()
    {
        $response = $this->json('GET', route('api.expense_category.index'));

        $response->assertJsonStructure([
            'data' => [
                "expense_categories" => [
                    '*' => $this->formatData
                ]
            ]
        ]);
    }
}
