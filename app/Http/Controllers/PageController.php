<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Config;
use App\Models\Attachment;
use App\Models\Disposition;
use App\Models\SignatureAndParaf;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateConfigRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.dashboard', [
            'greeting' => 'Selamat Datang!',
            'currentDate' => Carbon::now()->isoFormat('dddd, D MMMM YYYY'),
            'activeUser' => User::count(),
            'totalEmployees' => Employee::count(),
            'activeEmployees' => Employee::where('is_active', 1)->count(),
            'inactiveEmployees' => Employee::where('is_active', 0)->count(),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('pages.profile', [
            'data' => auth()->user(),
        ]);
    }

    public function profileUpdate(UpdateUserRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            $data = $request->validated();

            if ($request->hasFile('profile_picture')) {
                $old = $user->profile_picture;

                if (str_contains($old, '/storage/avatars/')) {
                    $path = parse_url($old, PHP_URL_PATH);
                    Storage::delete(str_replace('/storage', 'public', $path));
                }

                $filename = time() . '-' . $request->file('profile_picture')->getClientOriginalName();
                $request->file('profile_picture')->storeAs('public/avatars', $filename);
                $data['profile_picture'] = asset('storage/avatars/' . $filename);
            }

            $user->update($data);
            return back()->with('success', __('menu.general.success'));

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deactivate(): RedirectResponse
    {
        try {
            auth()->user()->update(['is_active' => false]);
            Auth::logout();
            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function settings(Request $request): View
    {
        return view('pages.setting', [
            'configs' => Config::all(),
        ]);
    }

    public function settingsUpdate(UpdateConfigRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            foreach ($request->validated() as $code => $value) {
                Config::where('code', $code)->update(['value' => $value]);
            }

            DB::commit();
            return back()->with('success', __('menu.general.success'));

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
