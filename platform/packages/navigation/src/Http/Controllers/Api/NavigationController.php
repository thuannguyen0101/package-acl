<?php

namespace Workable\Navigation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Navigation\Enums\ResponseEnum;
use Workable\Navigation\Http\Requests\NavigationRequest;
use Workable\Navigation\Services\NavigationService;
use Workable\Support\Traits\ResponseHelperTrait;

class NavigationController extends Controller
{
    use ResponseHelperTrait;

    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
//        $this->middleware('acl_permission:navigation_list')->only('index');
//        $this->middleware('acl_permission:navigation_create')->only('store');
//        $this->middleware('acl_permission:navigation_update')->only('show');
//        $this->middleware('acl_permission:navigation_show')->only('update');
//        $this->middleware('acl_permission:navigation_delete')->only('destroy');

        $this->navigationService = $navigationService;
    }

    public function index(NavigationRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'navigations' => $navigations,
            ) = $this->navigationService->index($request->all());

        return $this->respondSuccess($message, $navigations);
    }

    public function store(NavigationRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'navigation' => $navigation,
            ) = $this->navigationService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $navigation);
    }

    public function show($id, NavigationRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'navigation' => $navigation,
            ) = $this->navigationService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $navigation);
    }

    public function update(int $id, NavigationRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'navigation' => $navigation,
            ) = $this->navigationService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $navigation);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->navigationService->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }
}
