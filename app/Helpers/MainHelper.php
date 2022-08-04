<?php

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

function search($query , $keyword)
{
    $products = $query->where('name','like','%'.$keyword.'%')
                      ->orWhere('description','like','%'.$keyword.'%')
                      ->orWhere('price','like','%'.$keyword.'%')
    
                      ->orWhereHas('brand',function($item) use($keyword){
                 $item->where('name','like','%'.$keyword.'%');
        
                    })->orWhereHas('category',function($item) use($keyword){
                 $item->where('name','like','%'.$keyword.'%');
       
                 })->get();

    return $products;
}



function paginateResponse( LengthAwarePaginator $opject, ResourceCollection $colection)
{
    return [
        'data'=> $colection,
        'links' => $opject['links'],
    ];
}