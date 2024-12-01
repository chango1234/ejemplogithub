<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginSqlServer(Request $request)
    {
        $nombreUsuario = $request->input('nombreUsuario');
        $password = $request->input('password');
    
        $usuario = DB::connection('sqlsrv')->selectOne("EXEC Permisos ?, ?", [$nombreUsuario, $password]);
    
        if ($usuario !== null) {

            if ($usuario->usuario === $nombreUsuario && $usuario->contraseña === $password) {

                Session::put('usuario_autenticado_sqlserver', $nombreUsuario);
                Session::put('usuario_id_sqlserver', $usuario->id_usuario);
                return response()->json(['success' => 'Inicio de sesión exitoso']);
            }
        }
    
        return response()->json(['error' => 'Credenciales incorrectas']);
    }

    


    public function loginMySql(Request $request)
    {
        $nombreUsuario = $request->input('nombreUsuarioMySql');
        $password = $request->input('passwordMySql');
    
        $usuario = DB::connection('mysql')->selectOne("CALL Permisos(?, ?)", [$nombreUsuario, $password]);
    
        if ($usuario !== null) {

            if ($usuario->usuario === $nombreUsuario && $usuario->contraseña === $password) {

                Session::put('usuario_autenticado_mysql', $nombreUsuario);
                Session::put('usuario_id_mysql', $usuario->id_usuario);
                return response()->json(['success' => 'Inicio de sesión exitoso']);
            }
        }
    
        return response()->json(['error' => 'Credenciales incorrectas']);
    }
}
