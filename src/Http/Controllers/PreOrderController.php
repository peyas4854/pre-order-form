<?php

namespace Peyas\PreOrderForm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Peyas\PreOrderForm\Http\Requests\PreOrderRequest;
use Peyas\PreOrderForm\Http\Resources\PreOrderResource;
use Peyas\PreOrderForm\Models\PreOrder;
use Peyas\PreOrderForm\Services\PreOrderService;
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
            $validated = $request->validated();
            $this->preOrderService->store($validated);
            return response()->json(['message' => 'Your Pre Order Store Successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order not created'], 400);
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
