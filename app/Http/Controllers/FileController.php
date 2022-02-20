<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
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
        $model = File::orderBy($column, $order);

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
     * @param  \App\Http\Requests\StoreFileRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreFileRequest $request)
    {
        $image = $request->file('image');
        $image_urn = $image->store('pet-shop');

        $file = new File([
            'nome' => $request->nome,
            'path' => $image_urn,
            'size' => $image->getSize(),
            'type' => $image->extension(),
        ]);

        return response()->json($file, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return File
     */
    public function show(File $file)
    {
        return $file;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFileRequest  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        Storage::delete($file->path);
        $image = $request->file('image');
        $image_urn = $image->store('pet-shop');
        $file->update([
            'nome' => $request->nome,
            'path' => $image_urn,
            'size' => $image->getSize(),
            'type' => $image->extension(),
        ]);
        return response()->json($file, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(File $file)
    {
        Storage::delete($file->path);

        $file->delete();
        return response()->json(['OK'], 200);
    }
}
