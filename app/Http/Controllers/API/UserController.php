<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        try { // try catch untuk menangkap error
            $request->validate([ // validasi berdasarkan name email password
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:255'],
                'password' => ['required', 'string', new Password],
            ]);

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            
            $user = User::where('email', $request->email)->first(); // ambil data user berdasarkan email untuk di daftarin

            $tokenResult = $user->createToken('authToken')->plainTextToken; // buat token untuk user | createToken itu fungsi larabel | kembalikan menjadi plain text token

            // kembalikan data user beserta token
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], "User berhasil di daftarkan");
        } catch (Exception $error) { // jika ada error atau gagal di tangkap di sini
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
            
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // simpan credentialnya
            $credentials = request(['email', 'password']); // masukin fungsi request yg berisikan array email dan password
            // cek email passwordnya bener atau ngga
            if(!Auth::attempt($credentials)) { // ini klo salah
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            // ini klo emailnya bener
            $user = User::where('email', $request->email)->first(); // where email yang tadi udah di masukin oleh user dan ambil data yang paling pertama

            // cek password
            if(! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            // ini klo passwordnya bener
            $tokenResult = $user->createToken('authToken')->plainTextToken; // bikin token dan kembaliannya plain text token
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    // untuk ambil data user
    public function fetch(Request $request) {
        return ResponseFormatter::success($request->user(), 'Data profile user berhasil diambil'); // ini udah ngecek otomatis dari middleware sanctum
    }

    public function updateProfile(Request $request) {
        $data = $request->all(); // ambil data usernya

        $user = Auth::user(); // ambil user yg sedang login
        $user->update($data); // ambil data dari request yg sudah masuk

        return ResponseFormatter::success($user, 'Profile Updated');
    }

    // Logout
    public function logout(Request $request) {
        $token = $request->user()->currentAccessToken()->delete(); // ini fungsi dari laravel
        return ResponseFormatter::success($token, 'Token Revoked'); 
    }
}
