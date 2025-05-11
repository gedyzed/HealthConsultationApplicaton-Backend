<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Agora\ChatTokenBuilder2;

class AgoraChatController extends Controller
{
    const Role_Rtm_User = 1;
    private $appId;
    private $appCertificate;
    public function __construct()
    {
        $this->appId = env('AGORA_APP_ID');
        $this->appCertificate = env('AGORA_APP_CERTIFICATE');
    }

    public function generateUserToken(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'expire' => 'nullable|integer'
        ]);

        $username = $request->input('username');
        $expire = $request->input('expire', 86400);
        $token = ChatTokenBuilder2::buildUserToken($this->appId, $this->appCertificate, $username, $expire);
        return response()->json(['token' => $token]);
    }

    public function generateToken()
    {
        $expire = 86400;
        return ChatTokenBuilder2::buildAppToken($this->appId, $this->appCertificate, $expire);
    }

    public function generateAppToken(){
        $token = $this->generateToken();
        return response()->json(['token' => $token]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $app_token = $this->generateToken();
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $app_token,
        ])->post('https://a41.chat.agora.io/411339661/1544363/users', [
            'username' => $request->username
        ]);

        return response()->json([
            'status' => $response->successful() ? "success" : "error",
            'data' => $response->json()
        ], $response->status());
    }
}
