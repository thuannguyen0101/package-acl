<?php

namespace Workable\Budget\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Budget\Enums\ResponseEnum;
use Workable\Budget\Http\Requests\ExpenseCategoryRequest;
use Workable\Budget\Services\ExpenseCategoryService;
use Workable\Support\Traits\ResponseHelperTrait;

class ExpenseCategoryController extends Controller
{
    use ResponseHelperTrait;

    protected $expenseCategoryService;

    public function __construct(
        ExpenseCategoryService $expenseCategoryService
    )
    {
        $this->middleware('acl_permission:expense_category_list')->only('index');
        $this->middleware('acl_permission:expense_category_create')->only('store');
        $this->middleware('acl_permission:expense_category_update')->only('show');
        $this->middleware('acl_permission:expense_category_show')->only('update');
        $this->middleware('acl_permission:expense_category_delete')->only('destroy');

        $this->expenseCategoryService = $expenseCategoryService;
    }

    public function index(ExpenseCategoryRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'expense_categories' => $expense_categories,
            ) = $this->expenseCategoryService->index($request->all());

        return $this->respondSuccess($message, $expense_categories);
    }

    public function store(ExpenseCategoryRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'expense_category' => $expense_category,
            ) = $this->expenseCategoryService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $expense_category);
    }

    public function show($id, ExpenseCategoryRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'expense_category' => $expense_category,
            ) = $this->expenseCategoryService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $expense_category);
    }

    public function update(int $id, ExpenseCategoryRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'expense_category' => $expense_category,
            ) = $this->expenseCategoryService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $expense_category);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->expenseCategoryService->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }
}
