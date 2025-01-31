<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $message = $request->input('message');

        // Emitir el evento de mensaje enviado
        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['message' => 'Mensaje enviado correctamente']);
    }
}
