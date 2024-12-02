<?php

use Tests\BaseAuthTest;
use Workable\Budget\Models\AccountMoney;

class AccountMoneyTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = [
//            'name'        => 'Test Account Money',
//            'description' => 'Test Account Money description',
        ];

        $this->formatData   = [
            'id',
            'tenant_id',
            'name',
            'description',
            'created_at',
        ];

        $this->accountMoney = AccountMoney::create([
            'tenant_id'      => get_tenant_id(),
            'name'           => 'Test Account Money 02',
            'description'    => 'Test Account Money description 02',
            'area_id'        => 0,
            'area_source_id' => 0,
            'created_by'     => get_user_id(),
            'updated_by'     => get_user_id(),
        ]);

        $this->updateUrl = route('api.account_money.update', $this->accountMoney->id);
    }

    public function test_create()
    {
        $response = $this->postJson(route('api.account_money.index'), $this->data);
        dd($response->json());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "account_money" => $this->formatData
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
                    "account_money" => $this->formatData
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
                    "account_money" => $this->formatData
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
        $response = $this->json('GET', route('api.account_money.index'));
        $response->assertJsonStructure([
            'data' => [
                "account_moneys" => [
                    '*' => $this->formatData
                ]
            ]
        ]);
    }
}
