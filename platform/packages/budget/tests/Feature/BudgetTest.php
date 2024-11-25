<?php

use Tests\BaseAuthTest;
use Workable\Budget\Enums\ExpenseCategoryEnum;
use Workable\Budget\Models\AccountMoney;
use Workable\Budget\Models\Budget;
use Workable\Budget\Models\ExpenseCategory;

class BudgetTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->accountMoney = AccountMoney::create([
            'tenant_id'      => get_tenant_id(),
            'name'           => 'Test Account Money 02',
            'description'    => 'Test Account Money description 02',
            'area_id'        => 0,
            'area_source_id' => 0,
            'created_by'     => get_user_id(),
            'updated_by'     => get_user_id(),
        ]);

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

        $this->data = [
            'name'                => 'Test Budget',
            'description'         => 'Test Budget description',
            'expense_category_id' => $this->expenseCategory->id,
            'account_money_id'    => $this->accountMoney->id,
            'money'               => 100,
            'meta_content'        => [
                'content' => 'Test Budget 02',
                'money'   => 100,
            ],
        ];

        $this->budget = Budget::create([
            'tenant_id'           => get_tenant_id(),
            'name'                => 'Test Budget 02',
            'description'         => 'Test Budget description 02',
            'area_id'             => 0,
            'area_source_id'      => 0,
            'expense_category_id' => $this->expenseCategory->id,
            'account_money_id'    => $this->accountMoney->id,
            'money'               => 100,
            'meta_file'           => json_encode([]),
            'meta_content'        => json_encode([
                'content' => 'Test Budget 02',
                'money'   => 100,
            ]),
            'created_by'          => get_user_id(),
            'updated_by'          => get_user_id(),
        ]);

        $this->formatData = [
            'id',
            'tenant_id',
            'name',
            'description',
            'created_at',
        ];

        $this->updateUrl = route('api.budget.update', $this->budget->id);
    }

    public function test_create()
    {
        $response = $this->postJson(route('api.budget.index'), $this->data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "budget" => $this->formatData
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
                    "budget" => $this->formatData
                ]
            ]);
    }

    public function test_update()
    {
        $response = $this->json('POST', $this->updateUrl, [
            'name'                => 'Test Account Money 03',
            'description'         => 'Test Account Money description 03',
            'expense_category_id' => $this->expenseCategory->id,
            'account_money_id'    => $this->accountMoney->id,
            'money'               => 101,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "budget" => $this->formatData
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
        $response = $this->json('GET', route('api.budget.index'));
        $response->assertJsonStructure([
            'data' => [
                "budgets" => [
                    '*' => $this->formatData
                ]
            ]
        ]);
    }
}
