<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPostRequest;
// use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Gate::allows('user_check')) {
            $userLogged = $request->user();

            if ($userLogged->can('canSeeAllUser', User::class)) {
                $users = User::with(['comments:id,author_id', 'orders:id,buyer_id', 'registeredAdress:id'])->get();

                return $users;
            } elseif ($userLogged->can('canSeeAllUserInCompany', User::class)) {
                $users = User::with(['comments:id,author_id', 'orders:id,buyer_id', 'registeredAdress:id'])
                    ->where('role', '=', 'company')
                    ->where('company_id', '=', $userLogged->company_id)
                    ->get();

                return $users;
            } else {
                return redirect('/api/users/'.$userLogged->id);
            }
        }

        return response()->json(['error' => 'need to be identified'], 403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPostRequest $request)
    {
        // dd($request);
        $newUserDatas = $request->validated();

        return response()->json($newUserDatas);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->load('comments', 'orders', 'registeredAdress', 'profilePicture');

            return $user;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'id not found', 'id' => $id], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $originalUser = User::findOrFail($id);

            if (isset($request->id)) {
                return response()->json(['error' => 'not found'], 404);
            }

            foreach ($request->request as $attributeName => $attributeValue) {
                $originalUser->$attributeName = $attributeValue;
            }
            $originalUser->save();

            return $originalUser;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'id not found', 'id' => $id], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $originalUser = User::findOrFail($id);
            $originalUser->delete();

            return $originalUser;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'id not found', 'id' => $id], 404);
        }
    }
}
