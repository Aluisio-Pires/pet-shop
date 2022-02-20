<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
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
        $model = User::orderBy($column, $order);

        if ($request->has('first_name')) {
            $model = $model->where('first_name', $request->first_name);
        }
        if ($request->has('email')) {
            $model = $model->where('email', $request->email);
        }
        if ($request->has('phone')) {
            $model = $model->where('phone_number', $request->phone);
        }
        if ($request->has('address')) {
            $model = $model->where('address', $request->address);
        }
        if ($request->has('created_at')) {
            $model = $model->where('created_at', $request->created_at);
        }
        if ($request->has('marketing')) {
            $model = $model->where('is_marketing', $request->marketing);
        }
        if ($request->has('limit')) {
            $model = $model->take($request->limit)->get();
        } else {
            $model = $model->paginate();
        }

        return response()->json($model, 200);
    }

    /**
     * Show the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userData()
    {
        $user = auth()->user();
        return response()->json($user, 200);
    }

    public function userOrders(Request $request)
    {
        $order='asc';
        $column ='created_at';

        $user = User::where('id', auth()->id())->first();


        if ($request->desc === 'true'){
            $order='desc';
        }
        if ($request->has('sortBy')){
            $column = $request->sortBy;
        }
        $orders = Order::where('user_uuid',$user->uuid)
            ->with('orderStatus', 'payment')->orderBy($column, $order);

        if ($request->has('limit')){
            $orders = $orders->take($request->limit)->get();
        }else {
            $orders = $orders->paginate();
        }


        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password','same:password', 'string'],
            'avatar' => ['string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'is_marketing' => ['required', 'string'],
        ]);

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_admin' => 0,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'is_marketing' => $request->is_marketing,
        ]);
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password','same:password', 'string'],
            'avatar' => ['string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'is_marketing' => ['required', 'string'],
        ]);

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_admin' => 1,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'is_marketing' => $request->is_marketing,
        ]);
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        if($this->admin()){
            return response()->json($user, 200);
        }else{
            return response()->json(['Error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password','same:password', 'string'],
            'avatar' => ['string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'is_marketing' => ['required', 'string'],
        ]);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'is_marketing' => $request->is_marketing,
        ]);

        return response()->json(['user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if($this->admin()){
            $user->delete();
            return response()->json(['OK' => 'deleted'], 200);
        }else{
            return response()->json(['Error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMe()
    {
        User::where('id', auth()->id())->delete();
        return response()->json(['OK' => 'deleted'], 200);
    }


    public function admin()
    {
        $user = User::where('id', auth()->id())->first();
        if($user->is_admin){
            return true;
        }else{
            return false;
        }
    }

}
