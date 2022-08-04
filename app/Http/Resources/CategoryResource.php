<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    
    
    public function toArray($request)
    {
            
    foreach($this->sub_categories as $cat){
        $subCats[] = $cat->name;
    }

    
        return [
                
             'id' => $this->id,
             'name'  =>$this->name,
             'status'  =>$this->status,
             'main category' => $this->main_category ? $this->main_category->name : null,
             'sub categories' => $subCats ?? null,
        

        ]; 
    }
}