<?php

namespace Workable\Navigation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Navigation\Enums\ResponseEnum;
use Workable\Navigation\Http\Requests\CategoryMultiRequest;
use Workable\Navigation\Services\NavigationService;
use Workable\Support\Traits\ResponseHelperTrait;

class NavigationController extends Controller
{
    use ResponseHelperTrait;

    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
//        $this->middleware('acl_permission:category_multi_list')->only('index');
//        $this->middleware('acl_permission:category_multi_create')->only('store');
//        $this->middleware('acl_permission:category_multi_update')->only('show');
//        $this->middleware('acl_permission:category_multi_show')->only('update');
//        $this->middleware('acl_permission:category_multi_delete')->only('destroy');

        $this->navigationService = $navigationService;
    }

    public function index(CategoryMultiRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'list_category_multi' => $list_category_multi,
            ) = $this->navigationService->index($request->all());

        return $this->respondSuccess($message, $list_category_multi);
    }

    public function store(CategoryMultiRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'category_multi' => $category_multi,
            ) = $this->navigationService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $category_multi);
    }

    public function show($id, CategoryMultiRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'category_multi' => $category_multi,
            ) = $this->navigationService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $category_multi);
    }

    public function update(int $id, CategoryMultiRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'category_multi' => $category_multi,
            ) = $this->navigationService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $category_multi);
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
