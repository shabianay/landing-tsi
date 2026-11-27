<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    /** Passwords that are rejected even when they pass the length/complexity rules. */
    private const BANNED_PASSWORDS = ['password', 'password123', 'admin123', 'thesolutionid', 'qwerty123'];

    public function edit(Request $request): View
    {
        return view('admin.account.edit', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'current_password' => ['required', 'current_password'],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
        ], [
            'name' => 'nama',
            'current_password' => 'password saat ini',
        ]);

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.account.edit')->with('success', 'Nama dan email berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(12)->letters()->mixedCase()->numbers(),
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (in_array(Str::lower((string) $value), self::BANNED_PASSWORDS, true)) {
                        $fail('Password ini terlalu umum. Gunakan password lain.');
                    }
                },
            ],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ], [
            'current_password' => 'password saat ini',
            'password' => 'password baru',
        ]);

        $user->forceFill([
            'password' => $data['password'],
            'remember_token' => Str::random(60),
        ])->save();

        // Sign out every other browser/device that still uses the old password.
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        $request->session()->regenerate();
        $request->session()->forget('admin_weak_password');

        return redirect()->route('admin.account.edit')->with('success', 'Password berhasil diganti. Perangkat lain yang login dengan akun ini sudah dikeluarkan.');
    }
}
