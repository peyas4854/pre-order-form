<?php

namespace Peyas\PreOrderForm\Services;

use Illuminate\Support\Facades\Notification;
use Peyas\PreOrderForm\Models\PreOrder;
use Peyas\PreOrderForm\Notifications\AdminPreOrderNotification;
use Peyas\PreOrderForm\Notifications\UserPreOrderNotification;

class PreOrderService
{

    protected $preOrder;

    public function __construct(PreOrder $preOrder)
    {
        $this->preOrder = $preOrder;
    }

    public function index($request)
    {
        $query = PreOrder::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Adding a matching score based on search criteria
            $query->selectRaw('*,
            (CASE
                WHEN name LIKE ? THEN 2
                WHEN email LIKE ? THEN 1
                WHEN phone LIKE ? THEN 1
                ELSE 0
            END) as match_score',
                ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"]);

            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Ordering by match score
        if ($request->has('order_by') && $request->order_by === 'match_score') {
            $query->orderBy('match_score', 'desc');
        }

        // Default ordering by created_at if no specific order is given
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $preOrders = $query->paginate($perPage);

        return $preOrders;

    }

    /**
     * Store a new pre-order.
     *
     * @param array $data
     * @return PreOrder
     */
    public function store(array $data)
    {

        $preOrder = $this->preOrder->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'product_id' => $data['product_id'],
        ]);
        // Send notification to admin first
        Notification::route('mail', 'admin@admin.com')
            ->notify(new AdminPreOrderNotification($preOrder));

        // Once admin receives, queue the user email notification
        Notification::route('mail', $preOrder->email)
            ->notify(new UserPreOrderNotification($preOrder));

        return $preOrder;
    }

    public function delete($preOrder)
    {
        $preOrder->delete();
    }

}
