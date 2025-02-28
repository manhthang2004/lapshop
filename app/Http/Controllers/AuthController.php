<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

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
        $request->validate(['email' => 'required|email|exists:users,email']);

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
        $role = Auth::user()->role;  
        return view('auth.change_password', compact('role'));  
    }



    public function changePassword(Request $request)
    {
        $user = Auth::user();

        Log::info('Mật khẩu cũ trong cơ sở dữ liệu:', ['stored_password' => $user->password]);
        Log::info('Mật khẩu cũ người dùng nhập vào:', ['current_password' => $request->current_password]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng.']);
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('dashboard')->with('status', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset_password', compact('token'));
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Liên kết đặt lại mật khẩu đã được gửi!')
            : back()->withErrors(['email' => 'Không tìm thấy tài khoản với địa chỉ email này.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Mật khẩu đã được thay đổi thành công!');
        } else {
            return back()->withErrors(['email' => 'Đặt lại mật khẩu thất bại. Đảm bảo rằng liên kết và token là hợp lệ.']);
        }
    }
}
