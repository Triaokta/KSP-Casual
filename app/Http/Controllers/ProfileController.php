<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        return view('pages.profile', ['data' => $user]);
    }

    /**
     * Memperbarui data profil pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ];
        
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()];
        }
        
        $validator = Validator::make($request->all(), $rules, [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->has('reset_avatar')) {

            if ($user->profile_picture && !str_contains($user->profile_picture, 'ui-avatars.com') && !str_contains($user->profile_picture, 'http')) {
                $oldPath = str_replace('storage/', '', $user->profile_picture);
                Storage::disk('public')->delete($oldPath);
            }
            
            $user->profile_picture = null;
        }
        
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && !str_contains($user->profile_picture, 'ui-avatars.com') && !str_contains($user->profile_picture, 'http')) {
                $oldPath = str_replace('storage/', '', $user->profile_picture);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = asset('storage/' . $path);
        }
        
        if ($request->filled('current_password')) {

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai'])->withInput();
            }
            
            $user->password = Hash::make($request->new_password);
        }
        
        $user->save();
        
        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
