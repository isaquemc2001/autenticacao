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
        
        if (isset($cpf_banco['cpf']) == null){
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

        $cpf = $request->cpf;
        $password = $request->password;

        if (!Auth::attempt(json_decode($mydados, true))) {
            return response([
                'message' => 'Credencias inválidas',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60);

        return redirect()->route('api.user')->withCookie($cookie);

        // $user = Auth::table('usuario')->select()->guard('api');

        // $token = $user->createToken('token')->plainTextToken;

        // $cookie = cookie('jwt', $token, 60);

        // return response([
        //     'message' => 'Sucesso'
        // ])->withCookie($cookie);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Sucesso'
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }
}
