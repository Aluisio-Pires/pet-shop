<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        $model = Category::orderBy($column, $order);

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
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return Category
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category([
            'title' => $request->title,
            'slug' => $request->slug,
        ]);
        $category->save();

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return Category
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return Category
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'title' => $request->title,
            'slug' => $request->slug,
        ]);
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['OK'], 200);
    }
}
