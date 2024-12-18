<?php

namespace Workable\Contract\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Contract\Enums\ResponseEnum;
use Workable\Contract\Http\Requests\CRMContractRequest;
use Workable\Contract\Services\CRMContractService;
use Workable\Support\Traits\ResponseHelperTrait;

class CRMContractController extends Controller
{
    use ResponseHelperTrait;

    protected $service;

    public function __construct(
        CRMContractService $service
    )
    {
        $this->service = $service;
    }

    public function index(CRMContractRequest $request)
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

    public function store(CRMContractRequest $request)
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

    public function update(CRMContractRequest $request, int $id)
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

    public function show(CRMContractRequest $request, int $id)
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
}
