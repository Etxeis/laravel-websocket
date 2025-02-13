<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

use App\Models\User;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        $user->token_version += 1;
        $user->save();

        $token = auth()->attempt($credentials);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'redirect' => url('/home?user=' . $user->id), // URL para redirección
        ]);
    }


    public function register(Request $request){
        Log::info(message: 'Here');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                Password::min(8) // Contraseña de al menos 8 caracteres
                    ->letters() // Requiere al menos una letra
                    ->mixedCase() // Requiere mayúsculas y minúsculas
                    ->numbers() // Requiere al menos un número
                    ->symbols() // Requiere al menos un símbolo
                    ->uncompromised(), // Verifica que no esté en bases de datos comprometidas
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function home(Request $request)
    {
        // Obtener el token desde localStorage (enviado en el header Authorization)
        $token = $request->header('Authorization');

        if (!$token) {
            return redirect()->route('login'); // Si no hay token, redirigir a login
        }

        // Intentar autenticar al usuario con el token
        $user = Auth::guard('api')->setToken(str_replace('Bearer ', '', $token))->user();

        if (!$user) {
            return redirect()->route('login'); // Si el token no es válido, redirigir
        }

        return view('home', [
            'nombre' => $user->name,
            'correo' => $user->email
        ]);
    }

}
