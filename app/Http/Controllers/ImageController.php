<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Carbon\Carbon;
use Facade\FlareClient\Time\Time;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        // if($request->hasFile('image')){
        //     $image = $request->file('image');
        //     $file_name = Time().'-'.$image->getClientOriginalName();
        //     $file_path = public_path().'/images';
        //     $image->move($file_path,$file_name);

        //    $store = Image::create(['file_name'=>$file_name,'imageable_type'=>'App\Models\Brand','imageable_id'=>'1']);
        //     return response()->json($store);
        // } 
        // return response()->json('image not found');

     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $image = Image::find($id)->delete();
    }
}