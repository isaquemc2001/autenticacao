<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $cpf =  $request->cpf;
        $password = $request->password;
        // exit;

        $cpf_banco = User::select('cpf')->where('cpf', $cpf)->get()->first();

        if (isset($cpf_banco['cpf']) == null) {
            $cpf_banco['cpf'] = '';
        }

        if ($cpf_banco['cpf'] == isset($cpf)) {
            return response()->json(['msg' => 'Usuário Já Cadastrado'], JsonResponse::HTTP_UNAUTHORIZED);
        } else {
            User::create([
                'cpf' => $cpf,
                'password' => Hash::make($password)
            ]);
            return response()->json(['msg' => 'Usuário Cadastrado'], JsonResponse::HTTP_ACCEPTED);
        }

        // return $user;
    }

    public function login(Request $request)
    {
        // $dados = request(['cpf', 'password']);
        // // if (!Auth::attempt($dados)) {
        // //     return response()->json([
        // //         'status_code' => 500,
        // //         'message' => 'Unauthorized'
        // //     ]);
        // // }
        $user = User::where('cpf', $request->cpf)->first();
        
        if (Hash::check($request->password, $user->password)) {
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $cookie = cookie('jwt', $tokenResult, 60);

            return response()->json([
                'token' => $tokenResult
            ], JsonResponse::HTTP_ACCEPTED)->withCookie($cookie);
        }else{
            return response()->json([
                'msg' => 'Senha inválida'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Sucesso'
        ])->withCookie($cookie);

        // $request->user()->currentAccessToken()->delete();
        // return response()->json([
        //     'status_code' => 200,
        //     'message' => 'Token deleted'
        // ]);

    }

    public function user()
    {
        return Auth::user();
    }
}
