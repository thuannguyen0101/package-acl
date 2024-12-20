<?php

namespace Workable\HRM\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\Requests\LeaveRequestRequest;
use Workable\HRM\Services\LeaveRequestService;
use Workable\Support\Traits\ResponseHelperTrait;

class LeaveRequestController extends Controller
{
    use ResponseHelperTrait;

    protected $service;

    public function __construct(LeaveRequestService $service)
    {
        $this->service = $service;
    }

    public function index(LeaveRequestRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'items' => $items,
            ) = $this->service->index($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $items);
    }

    public function store(LeaveRequestRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'item' => $item,
            ) = $this->service->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $item);
    }

    public function update(int $id, LeaveRequestRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'item' => $item,
            ) = $this->service->update($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $item);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'item' => $item,
            ) = $this->service->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $item);
    }

    public function show(int $id, LeaveRequestRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'item' => $item,

            ) = $this->service->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $item);
    }

    public function getUser(LeaveRequestRequest $request)
    {
        $request = $request->all();

        $request['filter_base'] = [
            ['user_id', '=', get_user_id()]
        ];

        list(
            'status' => $status,
            'message' => $message,
            'items' => $items,

            ) = $this->service->index($request);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $items);
    }
}
