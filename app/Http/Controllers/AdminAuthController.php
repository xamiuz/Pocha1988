<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan formulir input password admin.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('admin_authenticated') === true) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Proses verifikasi password admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password admin wajib diisi.',
        ]);

        $masterPassword = config('admin.password', 'admin1988');

        // Timing-safe string comparison untuk mencegah timing attack
        if (hash_equals((string) $masterPassword, (string) $validated['password'])) {
            $request->session()->regenerate();
            $request->session()->put('admin_authenticated', true);
            $request->session()->put('admin_logged_in_at', now()->toDateTimeString());

            $intended = $request->session()->pull('url.intended', route('admin.dashboard'));

            return redirect()->to($intended)->with('success', 'Berhasil masuk ke Panel Admin 1988 Pocha!');
        }

        return back()
            ->withInput()
            ->withErrors([
                'password' => 'Password admin salah! Silakan periksa kembali.',
            ]);
    }

    /**
     * Logout dan hapus sesi admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_authenticated', 'admin_logged_in_at']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari Panel Admin.');
    }
}
