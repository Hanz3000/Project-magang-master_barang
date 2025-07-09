<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // gunakan model Admin, bukan User

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login khusus admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('barang.index');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    // Tampilkan form registrasi untuk admin
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi → langsung ke tabel admins
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('barang.index');
    }

    // Proses logout untuk admin
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function index()
    {
        return redirect()->route('barang.index');
    }
}
