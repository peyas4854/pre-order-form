<?php

namespace Peyas\PreOrderForm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Peyas\PreOrderForm\Http\Requests\PreOrderRequest;
use Peyas\PreOrderForm\Http\Resources\PreOrderResource;
use Peyas\PreOrderForm\Services\PreOrderService;

class PreOrderController extends Controller
{
    protected $preOrderService;

    public function __construct(PreOrderService $preOrderService)
    {
        $this->preOrderService = $preOrderService;
    }

    public function index(Request $request)
    {
        $preOrders = $this->preOrderService->index($request);
        return PreOrderResource::collection($preOrders);


    }

    public function store(PreOrderRequest $request)
    {
        // TODO : implement recaptcha validation , Send Notification to Admin and User
        try {
            $validated = $request->validated();
            $this->preOrderService->store($validated);
            return response()->json(['message' => 'Your Pre Order Store Successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order not created'], 400);
        }
    }
}
