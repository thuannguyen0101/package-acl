<?php

namespace Workable\Budget\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\Budget\Enums\ResponseEnum;
use Workable\Budget\Http\Requests\BudgetRequest;
use Workable\Budget\Services\BudgetService;
use Workable\Support\Traits\ResponseHelperTrait;

class BudgetController extends Controller
{
    use ResponseHelperTrait;

    protected $budgetService;

    public function __construct(BudgetService $budgetService)
    {
        $this->middleware('acl_permission:budget_list')->only('index');
        $this->middleware('acl_permission:budget_create')->only('store');
        $this->middleware('acl_permission:budget_update')->only('show');
        $this->middleware('acl_permission:budget_show')->only('update');
        $this->middleware('acl_permission:budget_delete')->only('destroy');

        $this->budgetService = $budgetService;
    }

    public function index(BudgetRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'budgets' => $budgets,
            ) = $this->budgetService->index($request->all());

        return $this->respondSuccess($message, $budgets);
    }

    public function store(BudgetRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'budget' => $budget,
            ) = $this->budgetService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $budget);
    }

    public function show($id, BudgetRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'budget' => $budget,
            ) = $this->budgetService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $budget);
    }

    public function update(int $id, BudgetRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'budget' => $budget,
            ) = $this->budgetService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $budget);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->budgetService->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }
}
