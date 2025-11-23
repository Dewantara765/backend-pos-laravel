<?php

namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\ValidationException;
    use Illuminate\Http\JsonResponse;
    use Validator;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
        {


            $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',

        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);

            $success['token'] =  $user->createToken('authToken')->plainTextToken;
            $success['name'] =  $user->name;



            return response()->json([
                'message' => 'User created successfully.',
            ], 200);

        }

        public function login(Request $request) : JsonResponse
        {
             if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        public function logout(Request $request): JsonResponse
        {
            $request->user()->currentAccessToken()->delete();
            return $this->sendResponse([], 'User logout successfully.');
        }

        public function user(Request $request)
        {
            $user = Auth::user();
            return $this->sendResponse($user, 'User details.');
        }
}
