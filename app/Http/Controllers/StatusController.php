<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class StatusController extends Controller
{

    consT MODEL_NAMESPACE = '\App\Models\\'; 
    
    consT RESOURCE_NAMESPACE = '\App\Http\Resources\\'; 


    
    public function __invoke($model,$id)
    {
        
        $class = self::MODEL_NAMESPACE.ucfirst($model);
        $resource = self::RESOURCE_NAMESPACE.ucfirst($model).'Resource';

        $item = $class::findOrFail($id);
        
        if($item){
            $item->update(['status'=>!$item->status]);
            
            return $this->respondWithSuccess(new $resource($item));
        }


    }
}