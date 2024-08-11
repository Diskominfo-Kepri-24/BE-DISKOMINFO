<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use Carbon\Carbon;

class VisitController extends Controller
{
    // INI UNTUK SETIAP KALI IP ADDRESS SAMA DI TAMBAHKAN VISIT_COUNTNYA
    // public function logVisit(Request $request)
    // {
    //     $ipAddress = $request->ip();
    //     $now = Carbon::now();

    //     $visit = Visit::where('ip_address', $ipAddress)->first();

    //     if ($visit) {
    //         $visit->last_activity = $now;
    //         $visit->is_online = true;
    //         $visit->visit_count += 1;
    //         $visit->save();
    //     } else {
    //         Visit::create([
    //             'ip_address' => $ipAddress,
    //             'is_online' => true,
    //             'last_activity' => $now,
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }
    // INI UNTUK SETIAP KALI IP ADDRESS SAMA HANYA DI TAMBAHKAN SEKALI SAJA, HANYA IP ADDRESS
    // YANG BERBEDA BARU DITAMBAHAKAN
    public function logVisit(Request $request)
{
    $ipAddress = $request->ip();
    $now = Carbon::now();

    $visit = Visit::where('ip_address', $ipAddress)->first();

    if ($visit) {
        // Jika IP address sudah ada, hanya update aktivitas terakhir tanpa menambah kunjungan
        $visit->last_activity = $now;
        $visit->is_online = true;
        $visit->save();
    } else {
        // Jika IP address baru, buat entri baru dengan kunjungan awal 1
        Visit::create([
            'ip_address' => $ipAddress,
            'is_online' => true,
            'last_activity' => $now,
            'visit_count' => 1, // Mulai dengan 1 kunjungan
        ]);
    }

    return response()->json(['success' => true]);
}


    public function updateOnlineStatus()
    {
        $timeout = Carbon::now()->subMinutes(5); // Pengguna dianggap offline setelah 5 menit tidak aktif
        Visit::where('last_activity', '<', $timeout)->update(['is_online' => false]);

        $onlineUsersCount = Visit::where('is_online', true)->count();

        return response()->json(['online_users_count' => $onlineUsersCount]);
    }

    public function getStatistics()
    {
        $today = Carbon::now()->startOfDay();
        $yesterday = Carbon::now()->subDay()->startOfDay();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $startOfLastMonthEnd = Carbon::now()->startOfMonth()->subSecond();

        $stats = [
            'online_users' => Visit::where('is_online', true)->count(),
            'today' => Visit::where('last_activity', '>=', $today)->sum('visit_count'),
            'yesterday' => Visit::whereBetween('last_activity', [$yesterday, $today])->sum('visit_count'),
            'this_month' => Visit::where('last_activity', '>=', $startOfMonth)->sum('visit_count'),
            'last_month' => Visit::whereBetween('last_activity', [$startOfLastMonth, $startOfLastMonthEnd])->sum('visit_count'),
        ];

        return response()->json($stats);
    }
}

