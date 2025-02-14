<?php

namespace Mamun\ShopPreOrder\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Mamun\ShopPreOrder\Events\PreOrderCreated;
use Mamun\ShopPreOrder\Http\Requests\StorePreOrderRequest;
use Mamun\ShopPreOrder\Http\Resources\PreOrderResource;
use Mamun\ShopPreOrder\Models\PreOrder;

class PreOrderController
{
    private function respond($data = null, $message = null, $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    /**
     * Display a listing of the pre-orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $search = $request->input('search');

        // Base query with search filters
        $query = PreOrder::query();
        if ($search) {
            $search = strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) ILIKE ?', ["%$search%"])
                ->orWhereRaw('LOWER(email) ILIKE ?', ["%$search%"]);
            });
        } else {
            $query->orderByDesc('id');
        }

        // Retrieve matching records
        $preOrders = $query->get();

        // Return early if no records found
        if ($preOrders->isEmpty()) {
            return $this->respond(null, 'No pre-orders found.', 404);
        }

        // Sort by maximum matching ratio
        $preOrders = $preOrders->sortByDesc(function ($preOrder) use ($search) {
            similar_text(strtolower($preOrder->name), $search, $nameMatch);
            similar_text(strtolower($preOrder->email), $search, $emailMatch);
            return max($nameMatch, $emailMatch);
        });

        // Paginate sorted results
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = config('grocery.pagination.per_page');
        $currentPageResults = $preOrders->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedResults = new LengthAwarePaginator(
            PreOrderResource::collection($currentPageResults),
            $preOrders->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return $this->respond($paginatedResults);
    }

    /**
     * Store a newly created pre-order in storage.
     *
     * @param  \Mamun\ShopPreOrder\Http\Requests\StorePreOrderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePreOrderRequest $request): PreOrderResource|JsonResponse
    {
        // Create a new pre-order
        $preOrder = PreOrder::create($request->validated());

        // Trigger the PreOrderCreated event to send emails
        Event::dispatch(new PreOrderCreated($preOrder));

        return $this->respond(new PreOrderResource($preOrder), 'Pre-order created successfully.', 201);
    }

    /**
     * Display the specified pre-order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): PreOrderResource|JsonResponse
    {
        $preOrder = PreOrder::findOrFail($id);

        return $this->respond(new PreOrderResource($preOrder));
    }

    /**
     * Update the specified pre-order in storage.
     *
     * @param  \Mamun\ShopPreOrder\Http\Requests\StorePreOrderRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StorePreOrderRequest $request, $id): PreOrderResource|JsonResponse
    {
        $preOrder = PreOrder::findOrFail($id);

        $preOrder->update($request->validated());

        return $this->respond(new PreOrderResource($preOrder), 'Pre-order updated successfully.');
    }

    /**
     * Remove the specified pre-order from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $preOrder = PreOrder::findOrFail($id);

        $preOrder->delete();

        return $this->respond(null, 'Pre-order deleted successfully.');
    }
}
