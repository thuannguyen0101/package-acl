<?php

namespace Workable\Budget\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Workable\Budget\Models\AccountMoney;
use Workable\Budget\Models\ExpenseCategory;
use Workable\Support\Traits\ResponseHelperTrait;

class BudgetMiddleware extends BaseMiddleware
{
    use ResponseHelperTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantId        = get_tenant_id();
        $accountMoney    = AccountMoney::where('tenant_id', $tenantId)->exists();
        $expenseCategory = ExpenseCategory::where('tenant_id', $tenantId)->exists();

        if ($accountMoney && $expenseCategory) {
            return $next($request);
        } else {
            if (!$accountMoney) {
                return response()->json([
                    'status'  => 'error',
                    'message' => __('budget::api.not_data.account_money'),
                ], 403);
            }
            return response()->json([
                'status'  => 'error',
                'message' => __('budget::api.not_data.expense_categories'),
            ], 403);
        }
    }
}
