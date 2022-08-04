<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase;

 class TestLoginAdmin extends TestCase
{

    use CreatesApplication;

    private $token;

    public function getToken(){
        return $this->token;
    }

    public function setToken($token){
        $this->token = $token;
       
    }

 

    public function testLoginAdmin()
    {
        $adminData = ['email'=> 'admin@yahoo.com','password' => '12345678'];
        
        $results =  $this->json('POST','/api/admin/login',$adminData,['Accept' => 'application/json',
        ])
       
       ->assertStatus(200);

    
    //    ->assertJsonStructure([
    //       [
              
    //        'access_token',
    //        'token_type',
    //        'expires_in',
    //       ]
    //    ]);

      $this->setToken($results['access_token']);

    } 



    public function getHeaderData()
    {
        // dd($this->getToken());

        $token =  $this->getToken();
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "bearer $token" 
        ];
    }

    
}