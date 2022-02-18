<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $order = 'asc';
        $column = 'created_at';

        if ($request->desc === 'true') {
            $order = 'desc';
        }
        if ($request->has('sortBy')) {
            $column = $request->sortBy;
        }
        $model = Order::orderBy($column, $order)->with('user', 'orderStatus', 'payment');

        if ($request->has('limit')) {
            $model = $model->take($request->limit)->get();
        } else {
            $model = $model->paginate();
        }

        return response()->json($model, 200);
    }

    /**
     * Display a listing of the resource to populate a dashboard.
     *
     * @return string
     */
    public function dashboard(Request $request)
    {
        $order = 'asc';
        $column = 'created_at';

        if ($request->desc === 'true') {
            $order = 'desc';
        }
        if ($request->has('sortBy')) {
            $column = $request->sortBy;
        }
        $model = Order::orderBy($column, $order)->with('user', 'orderStatus', 'payment');

        return $this->OrderFilters($request, $model);
    }


    /**
     * Display a listing of the resource to all shipped orders.
     *
     * @return string
     */
    public function locator(Request $request)
    {
        $order = 'asc';
        $column = 'created_at';

        if ($request->desc === 'true') {
            $order = 'desc';
        }
        if ($request->has('sortBy')) {
            $column = $request->sortBy;
        }
        $model = Order::orderBy($column, $order)->with('user', 'orderStatus', 'payment')
            ->where('orderStatus.title', 'shipped');

        if ($request->has('orderUuid')) {
            $model = $model->where('uuid', $request->orderUuid);
        }
        if ($request->has('customerUuid')) {
            $model = $model->where('user_uuid', $request->customerUuid);
        }

        return $this->OrderFilters($request, $model);
    }

    /**
     * Download the resource.
     *
     * @return string
     */
    public function download(string $uuid)
    {
        $order = Order::where('uuid', $uuid)->with('user', 'orderStatus', 'payment')->get();
        $data = [
            'pdfName' =>$order->uuid,
            'store' => 'Store Name',
            'creation' => $order->created_at,
            'invoice' => 'Invoice Number',
            'customer' => $order->user,
            'address' => $order->address,
            'payment' => $order->payment,
            'item' => $order->products,
            'fee' => $order->delivery_fee,
            'total' => $order->amount,
        ];

        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download($order->uuid . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreOrderRequest $request
     * @return Order
     */
    public function store(StoreOrderRequest $request)
    {
        $amount=0;
        $fee=0;
        $products = json_decode($request->products);
        foreach ($products as $product){
            $item = Product::where('uuid', $product->product);
            if($item->price){
                $amount = $amount + ($item->price*$product->quantity);
            }
        }
        if($amount<500){
            $fee=15;
        }

        $order = new Order([
            'user_uuid' => $request->user_uuid,
            'order_status_uuid' => $request->order_status_uuid,
            'payment_uuid' => $request->payment_uuid,
            'products' => $request->products,
            'address' => $request->address,
            'delivery_fee' => $fee,
            'amount' => $amount,
        ]);
        $order->save();
        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return Order
     */
    public function show(Order $order)
    {
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateOrderRequest $request
     * @param \App\Models\Order $order
     * @return Order
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $amount=0;
        $fee=0;
        $products = json_decode($request->products);
        foreach ($products as $product){
            $item = Product::where('uuid', $product->product);
            if($item->price){
                $amount = $amount + ($item->price*$product->quantity);
            }
        }
        if($amount<500){
            $fee=15;
        }

        $order->update([
            'user_uuid' => $request->user_uuid,
            'order_status_uuid' => $request->order_status_uuid,
            'payment_uuid' => $request->payment_uuid,
            'products' => $request->products,
            'address' => $request->address,
            'delivery_fee' => $fee,
            'amount' => $amount,
        ]);
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['OK'], 200);
    }

    /**
     * @param Request $request
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function OrderFilters(Request $request, $model): \Illuminate\Http\JsonResponse
    {
        if ($request->has('dateRange')) {
            $ranges = json_decode($request->dateRange);
            $model = $model->whereDate('created_at', '>=', $ranges->from)
                ->whereDate('created_at', '<=', $ranges->to);
        }

        if ($request->has('fixRange')) {
            if ($request->fixRange === 'today') {
                $model = $model->whereDate('created_at', Carbon::today());
            } elseif ($request->fixRange === 'monthly') {
                $model = $model->whereMonth('created_at', Carbon::now()->month);
            } elseif ($request->fixRange === 'yearly') {
                $model = $model->whereYear('created_at', Carbon::now()->year);
            }
        }

        if ($request->has('limit')) {
            $model = $model->take($request->limit)->get();
        } else {
            $model = $model->paginate();
        }

        return response()->json($model, 200);
    }
}
