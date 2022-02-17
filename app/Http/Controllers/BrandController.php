<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\Request;

class BrandController extends Controller
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
        $model = Brand::orderBy($column, $order);

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
     * @param  \App\Http\Requests\StoreBrandRequest  $request
     * @return Brand
     */
    public function store(StoreBrandRequest $request)
    {

        $brand = new Brand([
            'title' => $request->title,
            'slug' => $request->slug,
        ]);
        $brand->save();

        return $brand;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return Brand
     */
    public function show(Brand $brand)
    {
        return $brand;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBrandRequest  $request
     * @param  \App\Models\Brand  $brand
     * @return Brand
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update([
            'title' => $request->title,
            'slug' => $request->slug,
        ]);
        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(['OK'], 200);
    }
}
