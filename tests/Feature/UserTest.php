<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{


    public function testRegisterSuccess(){

        $this->post('/api/register', [
            "name" => "Mahadi Dwi Nugraha",
            "email" => "mahadi@example.com",
            "password" => "mahadi123",
            "role" => 'guest'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "name" => "Mahadi Dwi Nugraha",
                    "email" => "mahadi@example.com",
                    // "updated_at" => "2024-07-03T04:43:26.000000Z",
                    // "created_at" => "2024-07-03T04:43:26.000000Z",
                    // "id" => 5
                ],
                // "access_token" => "5|TmFgOo3TEJpETTjud9tCaVMSK5TY20J1iyb0Pn9o62b9d200",
                "token_type" => "Bearer"
            ]);

    }

    public function testRegisterFailed(){
        $this->post('/api/register', [
            "name" => "",
            "email" => "",
            "password" => "",
        ])->assertStatus(400)
            ->assertJson([
                "email" => [
                    "The email field is required."
                ],
                "password" => [
                    "The password field is required."
                ],
                "name" => [
                    "The name field is required."
                ],

            ]);
    }

    public function testRegisterEmailAlreadyExists(){

        $this->testRegisterSuccess();

        $this->post('/api/register', [
            "name" => "Mahadi Dwi Nugraha",
            "email" => "mahadi@example.com",
            "password" => "mahadi123",
            "role" => "siswa"
        ])->assertStatus(400)
            ->assertJson([
                "email" => [
                    "The email has already been taken."
                ]

            ]);
    }

    public function testLoginSuccess(){

        $this->seed([UserSeeder::class]);
        $this->post('/api/login', [
            "email" => "mahadi@example.com",
            "password" => "mahadi123"
        ])->assertStatus(200)
            ->assertJson([
                "message" => "Login Success",
                // "access_token" => "",
                "token_type" => "Bearer",
            ]);
    }

    public function testLoginFailedEmailNotFound(){
        $this->post('/api/login', [
            "email" => "mahadi@example.com",
            "password" => "mahadi123",
        ])->assertStatus(401)
            ->assertJson([
                "message" => "Unauthorized"
            ]);
    }

    public function testLoginFailedPasswordWrong(){
        
        $this->seed([UserSeeder::class]);

        $this->post('/api/login', [
            "email" => "mahadi@example.com",
            "password" => "passwordsalah123",
        ])->assertStatus(401)
            ->assertJson([
                "message" => "Unauthorized"
            ]);

    }

    public function testGetUserData(){

        $this->testLoginSuccess();

        $this->get('/api/user', [
        ])->assertStatus(200)
            ->assertJson([
                // "id" => 
                "name" => "Mahadi Dwi Nugraha",
                "email" => "mahadi@example.com",
                
            ]);


    }

    public function testLogout(){

        $this->testLoginSuccess();
        $this->delete('/api/logout', [

        ])->assertStatus(200)
            ->assertJson([
                "message" => "logout success"
            ]);

    }
}
