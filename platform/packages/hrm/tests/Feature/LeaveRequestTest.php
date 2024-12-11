<?php

use Carbon\Carbon;
use Tests\BaseAuthTest;
use Workable\HRM\Enums\LeaveRequestEnum;
use Workable\HRM\Models\LeaveRequest;

class LeaveRequestTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = array(
            'user_id'     => $this->user->id,
            'tenant_id'   => $this->user->tenant_id,
            'leave_type'  => LeaveRequestEnum::LEAVE_APPLICATION,
            'start_date'  => Carbon::parse('08:00')->format('Y-m-d H:i'),
            'end_date'    => Carbon::parse('12:00')->format('Y-m-d H:i'),
            'reason'      => "Em xin nghỉ do ốm.",
            'approved_by' => $this->user->id,
        );

        $this->item = LeaveRequest::query()->create(array(
            'user_id'     => $this->member->id,
            'tenant_id'   => $this->user->tenant_id,
            'leave_type'  => LeaveRequestEnum::LEAVE_APPLICATION,
            'start_date'  => Carbon::parse('08:00')->format('Y-m-d H:i:s'),
            'end_date'    => Carbon::parse('12:00')->format('Y-m-d H:i:s'),
            'reason'      => "Em xin nghỉ do ốm x2.",
            'status'      => LeaveRequestEnum::PENDING,
            'approved_by' => $this->user->id,
        ));
    }

    public function test_create()
    {
        $response = $this->json('POST', route('api.leave-request.store'), $this->data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_update()
    {
        $this->data['status'] = LeaveRequestEnum::APPROVED;
        $response             = $this->json('POST', route('api.leave-request.update', $this->item->id), $this->data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_delete()
    {
        $response = $this->json('DELETE', route('api.leave-request.destroy', $this->item->id));
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_show()
    {
        $response = $this->json('GET', route('api.leave-request.show', $this->item->id), [
            'with' => 'user,approvedBy,tenant',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_index()
    {
        $response = $this->json('GET', route('api.leave-request.index'), [
            'with' => 'user,approvedBy,tenant',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_index_user()
    {
        $response = $this->json('GET', route('api.leave-request.get_user'), [
            'with' => 'user,approvedBy,tenant',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }
}
