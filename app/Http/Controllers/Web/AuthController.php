<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Temporary debug
        \Illuminate\Support\Facades\Log::info('LOGIN ATTEMPT', [
            'email' => $credentials['email'],
            'password_length' => strlen($credentials['password']),
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        \Illuminate\Support\Facades\Log::info('USER FOUND', [
            'found' => $user ? true : false,
            'is_active' => $user?->is_active,
            'password_match' => $user ? \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password) : false,
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            \Illuminate\Support\Facades\Log::info('LOGIN SUCCESS', ['user_id' => Auth::id()]);

            $intended = redirect()->getIntendedUrl();
            if ($intended && Str::contains($intended, '/admin')) {
                return redirect('/');
            }

            return redirect()->intended('/');
        }

        \Illuminate\Support\Facades\Log::warning('LOGIN FAILED - Auth::attempt returned false');

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'referral_code' => strtoupper(Str::random(8)),
            'is_active' => true,
        ]);

        $user->assignRole('Customer');

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showProfile()
    {
        return Inertia::render('Profile');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', app()->getLocale() === 'ar' ? 'تم تحديث الملف الشخصي بنجاح!' : 'Profile updated successfully!');
    }
}
