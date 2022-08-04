<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestLoginAdmin;

class BrandTest extends TestLoginAdmin
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBrandIndex()
    {
       $this->json('GET','/api/brand',[],$this->getHeaderData())
       
       ->assertStatus(200)
    
       ->assertJsonStructure([
          [
              
           'id',
           'name',
           'status',
           'image',
          ]
       ]);
       
    }

    
}