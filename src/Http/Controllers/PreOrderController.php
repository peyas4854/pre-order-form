<?php

namespace Peyas\PreOrderForm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Peyas\PreOrderForm\Http\Requests\PreOrderRequest;
use Peyas\PreOrderForm\Http\Resources\PreOrderResource;
use Peyas\PreOrderForm\Models\PreOrder;
use Peyas\PreOrderForm\Services\PreOrderService;
use Peyas\PreOrderForm\Services\RecaptchaService;
use Spatie\Permission\Middleware\PermissionMiddleware;


class PreOrderController extends Controller implements HasMiddleware
{
    protected $preOrderService;

    public function __construct(PreOrderService $preOrderService)
    {
        $this->preOrderService = $preOrderService;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('pre-order-list','api'), only:['index']),
            new Middleware(PermissionMiddleware::using('pre-order-show','api'), only:['show']),
            new Middleware(PermissionMiddleware::using('pre-order-delete','api'), only:['destroy']),
        ];
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {

        $preOrders = $this->preOrderService->index($request);
        return PreOrderResource::collection($preOrders);

    }

    public function store(PreOrderRequest $request)
    {
        try {
            // Begin the transaction
            DB::beginTransaction();

            // Get validated data
            $validatedData = $request->validated();

            // Validate reCAPTCHA
            $recaptchaService = new RecaptchaService(); // Instantiated directly in the method
            $recaptchaService->recaptchaValidate($validatedData, $request->ip());

            // Store the pre-order
            $this->preOrderService->store($validatedData);

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Your Pre Order Store Successfully'], 201);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            // Log the exception for debugging
            Log::error('PreOrder failed to store', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json(['message' => 'Order not created', 'error' => $e->getMessage()], 400);
        }
    }


    public function show(PreOrder $preOrder)
    {
        return new PreOrderResource($preOrder);
    }


    public function destroy(PreOrder $preOrder)
    {
        $this->preOrderService->delete($preOrder);
        return response()->json(['message' => 'Pre Order Deleted Successfully'], 200);
    }



}
