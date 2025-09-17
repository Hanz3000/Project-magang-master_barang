<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AuthController extends Controller
{
    // Form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login pakai NIP
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip'      => 'required',
            'password' => 'required',
        ], [
            'nip.required'      => 'NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('barang.index');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ])->withInput();
    }

    // Form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses register → masuk ke tabel admins
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|max:255',
            'nip'      => 'required|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name'     => $validatedData['name'],
            'nip'      => $validatedData['nip'],
            'password' => bcrypt($validatedData['password']),
        ]);

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('barang.index');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function index()
    {
        return redirect()->route('barang.index');
    }
}
