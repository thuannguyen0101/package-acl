<?php

namespace Workable\HRM\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\Requests\FineRequest;
use Workable\HRM\Services\PenaltyService;
use Workable\Support\Traits\ResponseHelperTrait;

class PenaltyController extends Controller
{
    use ResponseHelperTrait;

    protected $service;

    public function __construct(PenaltyService $service)
    {
        $this->service = $service;
    }

    public function index(FineRequest $request)
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

    public function store(FineRequest $request)
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

    public function update(int $id, FineRequest $request)
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

    public function show(int $id, FineRequest $request)
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
