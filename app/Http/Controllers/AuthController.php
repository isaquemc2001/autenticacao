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
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        
        echo $cpf =  $request->cpf; '<br>';
        echo $password = $request->password;
        exit;

        $cpf_banco = User::select('cpf')->where('cpf', $cpf)->get()->first();
        // echo $cpf_banco['cpf'];
        // exit;

        if ($cpf_banco['cpf'] == $cpf) {
            return "erro";
        } else {
            User::create([
                'cpf' => $cpf,
                'password' => Hash::make($pas)
            ]);
            return redirect()->route('api.login', ['cpf' => $cpf, 'password' => $password]);
        }

        // return $user;
    }

    public function login(Request $request, String $cpf, String $password)
    {

        $dados = (object) [
            'cpf' => $cpf,
            'password' => $password
        ];

        $mydados = json_encode($dados);

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
