<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy untuk testing, nanti bisa diambil dari database
        $todayLetterTransaction = 12;
        $activeEmployees = Employee::where('is_active', 1)->count();
        $inactiveEmployees = Employee::where('is_active', 0)->count();
        $todayOutgoingLetter = 4;

        $totalYesterday = 10; // nilai acuan untuk menghitung persen

        $percentageLetterTransaction = $totalYesterday > 0
            ? round((($todayLetterTransaction - $totalYesterday) / $totalYesterday) * 100, 2)
            : 0;

        $percentageIncomingLetter = 20;
        $percentageOutgoingLetter = -10;
        $percentageDispositionLetter = 5;

        $totalEmployees = Employee::count();

        return view('pages.dashboard', [
            'greeting' => 'Selamat Datang!',
            'currentDate' => Carbon::now()->translatedFormat('l, d F Y'),
            'activeEmployees' => $activeEmployees,
            'inactiveEmployees' => $inactiveEmployees,
            'totalEmployees' => $totalEmployees,

        ]);
    }
}
