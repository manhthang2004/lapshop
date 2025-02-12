<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công
            $user = Auth::user();
            if ($user->role == 1) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('dashboard');
        }
    
        return redirect()->back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }
    

    public function dashboard()
    {
        $user = Auth::user();

        if (is_null($user)) {
            return redirect()->route('login.form');
        }

        return view('dashboard', ['user' => $user]);
    }

    public function showAdmin()
    {
        $user = Auth::user();

        if (is_null($user)) {
            return redirect()->route('login.form');
        }

        return view('admin.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Hiển thị form đăng ký
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Tạo người dùng mới
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::attempt([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        return redirect()->route('dashboard');
    }

    public function forgotPassword(Request $request)
    {
        // Validate email
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        // Gửi email reset mật khẩu
        $response = Password::sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', 'We have e-mailed your password reset link!');
        }

        return back()->withErrors(['email' => 'Failed to send reset link']);
    }

    public function showChangePasswordForm()
    {
        $role = Auth::user()->role;  // Lấy thông tin role của user đang đăng nhập
        return view('auth.change_password', compact('role'));  // Truyền biến role vào view
    }
    

    // Xử lý thay đổi mật khẩu

    public function changePassword(Request $request)
    {
        $user = Auth::user();
    
        // Log mật khẩu hiện tại và mật khẩu cũ nhập vào để kiểm tra
        Log::info('Mật khẩu cũ trong cơ sở dữ liệu:', ['stored_password' => $user->password]);
        Log::info('Mật khẩu cũ người dùng nhập vào:', ['current_password' => $request->current_password]);
    
        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng.']);
        }
    
        // Tiến hành thay đổi mật khẩu mới nếu đúng mật khẩu cũ
        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => Hash::make($request->new_password)]);
    
        return redirect()->route('dashboard')->with('status', 'Mật khẩu đã được thay đổi thành công.');
    }
}