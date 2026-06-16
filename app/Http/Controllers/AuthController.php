<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Nama tidak boleh kosong.',
            'email.required'     => 'Email tidak boleh kosong.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Kata sandi tidak boleh kosong.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('aruna-app')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email tidak boleh kosong.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi tidak boleh kosong.',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('aruna-app')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => array_merge($user->toArray(), [
                'photo_url' => $user->photo 
                    ? asset('storage/' . $user->photo) 
                    : null,
            ]),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'user'   => array_merge($user->toArray(), [
                'photo_url' => $user->photo 
                    ? asset('storage/' . $user->photo) 
                    : null,
            ]),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'  => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'photo' => 'sometimes|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama kalau ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui',
            'user'    => array_merge($user->toArray(), [
                'photo_url' => $user->photo 
                    ? asset('storage/' . $user->photo) 
                    : null,
            ]),
        ]);
    }
}