<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Company;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|regex:/^[a-zA-Z0-9_!@#$%&]*$/|min:8|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
                'status' => 400,
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // $token = $user->createToken('API TOKEN')->plainTextToken;

            return response()->json([
                'message' => 'user Created Successfully!!',
                'results' => $user,
                // 'token' => $token,
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
                'status' => 400,
            ]);
        } else {
            $userdata = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            try {
                if (Auth::attempt($userdata)) {
                    $user = User::where('email', $request->email)->first();
                    $token = $user->createToken('API TOKEN')->plainTextToken;

                    return response()->json([
                        'success' => true,
                        'message' => 'Authorized',
                        'token' => $token,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => ['email' => ['invalid email']],
                    ]);
                }
            } catch (\Throwable $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    public function user(Request $request)
    {
        $query = User::select(
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        );

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->orWhere('id', 'like', '%' . $request->search . '%');
                $q->orWhere('name', 'like', '%' . $request->search . '%');
                $q->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy($request->column, $request->order);
        $count = $query->count();
        $user = $query
            ->offset($request->offset)
            ->limit($request->limit)
            ->get();

        // $query1 = User::select(
        //     'id',
        //     'name',
        //     'email',
        //     'created_at',
        //     'updated_at'
        // );
        // if($request->search){
        //     $query1->where(function ($q) use ($request) {
        //         $q->orWhere('id', 'like', '%' . $request->search . '%');
        //         $q->orWhere('name', 'like', '%' . $request->search . '%');
        //         $q->orWhere('email', 'like', '%' . $request->search . '%');
        //     });
        // }

        // $query1->orderBy($request->column, $request->order);
        // $count = $query1->orderBy($request->column, $request->order)->count();

        // ->where(function ($q) use ($request) {
        //     $q->orWhere('id', 'like', '%' . $request->search . '%');
        //     $q->orWhere('name', 'like', '%' . $request->search . '%');
        //     $q->orWhere('email', 'like', '%' . $request->search . '%');
        // })
        // ->get();
        /*} else {
            $column = $request->column;
            $order = $request->order;
            if ($column) {
                Log::info($request->column);
                Log::info($request->order);
                $user = User::select(
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                )
                    ->orderBy($request->column, $request->order)
                    ->get();
            } else {

                if ($request->page) {
                    Log::info($request->page);
                    $user = User::limit($request->limit)->get();
                } else {
                    Log::info('vvv');
                    $user = User::get();
                }
            }
        }*/

        return response()->json([
            'message' => 'user show',
            'data' => $user,
            'count' => $count,
        ]);
    }

    public function company()
    {
        $company = Company::all();
        return response()->json([
            'message' => 'company show',
            'data' => $company,
        ]);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
                'status' => 400,
            ]);
        } else {
            $create = Company::create([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            return response()->json([
                'message' => 'company Created Successfully!!',
                'results' => $create,
                'status' => 201,
            ]);
        }
    }
    public function delete($id)
    {
        $userDelete = User::where('id', $id)->delete();
        return response()->json([
            'message' => 'user deleted successfully',
            'id' => $id,
        ]);
    }

    public function edit($id)
    {
        $edit = User::where('id', $id)->first();
        return response()->json([
            'message' => 'user edit successfully',
            'edit' => $edit,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages(),
                'status' => 400,
            ]);
        } else {
            $user = User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            // Log::info($user, 'user---------------');
            $updateData = User::where('id',$id)->get();
            Log::info($updateData);
            return response()->json([
                'message' => 'user updated Successfully!!',
                'updateData' => $updateData,
                'status' => 201,
            ]);
        }
    }
}
