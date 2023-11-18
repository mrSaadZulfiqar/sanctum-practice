<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        if (count($users) > 0) {
            //if users found
            $response = [
                'message' => count($users) . " users found",
                'status' => '1',
                'users' => $users,
            ];
        } else {
            //if users not found
            $response = [
                'message' => count($users) . " users found",
                'status' => '0',
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required'],
    //         'email' => ['required', 'email', 'unique:users,email'],
    //         'password' => ['required', 'min:8'],
    //     ]);

    //     //return $request->all();

    //     $user = true;

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     } else {
    //         DB::beginTransaction();
    //         $data = [
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ];
    //         try {

    //             User::create($data);
    //             DB::commit();
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             //p($e->getMessage());
    //             $user = null;
    //         }
    //         if ($user != null) {
    //             //all OK
    //             return response()->json([
    //                 'message' => 'user registered successfully',
    //             ], 200);
    //         } else {
    //             //user not created
    //             return response()->json([
    //                 'message' => 'internal server error',
    //                 'error'=> $e->getMessage(),

    //             ], 500);
    //         }
    //     }

    //     return response()->json(['message' => 'User successfully created'], 201);
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //find method searches the record by id 
        $user = user::find($id);
        if (is_null($user)) {
            //user not found
            $response = [
                'message' => 'user not found',
                'status' => 0,
            ];
        } else {
            //user found
            $response = [
                'message' => 'user found',
                'status' => 1,
                'data' => $user,
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //find method searches the record by id 
        $user = User::find($id);
        // return $user;
        $responseCode = 0;
        if (is_null($user)) {
            //user not found
            $response = [
                'message' => 'user not found',
                'status' => 0,
            ];
            $responseCode = 404;
        } else {
            //user found
            DB::beginTransaction();
            try {
                //
                $user->name = $request->name;
                $user->email = $request->email;
                
                $user->save();
                DB::commit();

                $response = [
                    'message' => 'user pdated successfully',
                    'status' => 1,
                    'data' => $user,
                ];

                $responseCode = 202;
            } catch (\Exception $e) {
                DB::rollBack();
                $response = [
                    'message' => 'internal server error',
                    'status' => 0,
                    'error' => $e->getMessage(),
                ];
                $responseCode = 500;
            }
        }
        return response()->json($response, $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // return 1;
        $user = user::find($id);
        if (is_null($user)) {
            //user not found
            $response = [
                'message' => 'user does not exists',
                'status' => 0,
            ];
            $responseCode = 404;
        } else {
            //user found
            DB::beginTransaction();

            try {
                $user->delete();
                DB::commit();
                $response = [
                    'message' => 'user deleted successfully',
                    'status' => 1,
                ];
                $responseCode = 200;
            } catch (\Exception $e) {
                DB::rollBack();
                $response = [
                    'message' => 'Internal server error',
                    'error'=> $e->getMessage(),
                    'status' => 0,
                ];
                $responseCode = 500;
            }
        }
        return response()->json($response, $responseCode);
    }
}
