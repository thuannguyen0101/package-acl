<?php

namespace Workable\HRM\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\Requests\PenaltyRuleRequest;
use Workable\HRM\Services\PenaltyRuleService;
use Workable\Support\Traits\ResponseHelperTrait;

class PenaltyRuleController extends Controller
{
    use ResponseHelperTrait;

    protected $service;

    public function __construct(PenaltyRuleService $service)
    {
        $this->service = $service;
    }

    public function index(PenaltyRuleRequest $request)
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

    public function store(PenaltyRuleRequest $request)
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

    public function update(int $id, PenaltyRuleRequest $request)
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

    public function show(int $id, PenaltyRuleRequest $request)
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
}
