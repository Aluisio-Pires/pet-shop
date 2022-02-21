<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use App\Http\Requests\StoreOrderStatusRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $order='asc';
        $column ='created_at';

        if ($request->desc === 'true'){
            $order='desc';
        }
        if ($request->has('sortBy')){
            $column = $request->sortBy;
        }
        $model = OrderStatus::orderBy($column, $order);

        if ($request->has('limit')){
            $model = $model->take($request->limit)->get();
        }else{
            $model = $model->paginate();
        }

        return response()->json($model, 200);
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
     * @param  \App\Http\Requests\StoreOrderStatusRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderStatusRequest $request)
    {
        $status = new OrderStatus([
            'title' => $request->title,
        ]);
        $status->save();
        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return OrderStatus
     */
    public function show(OrderStatus $orderStatus)
    {
        return $orderStatus;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderStatus $orderStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderStatusRequest  $request
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return OrderStatus
     */
    public function update(UpdateOrderStatusRequest $request, OrderStatus $orderStatus)
    {
        $orderStatus->update([
            'title' => $request->title,
        ]);
        return $orderStatus;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderStatus  $orderStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();
        return response()->json(['OK'], 200);
    }
}
