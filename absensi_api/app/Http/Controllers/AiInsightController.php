<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;

class AiInsightController extends Controller
{
    /**
     * Show the AI Insight page.
     */
    public function index()
    {
        return view('rekap.ai-insight');
    }

    /**
     * Generate daily summary using Google Gemini AI.
     */
    public function generateDailySummary()
    {
        try {
            $today = Carbon::today();
            $attendances = Attendance::with('user.schoolClass')
                ->whereDate('date', $today)
                ->get();

            if ($attendances->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data absensi untuk hari ini.'
                ]);
            }

            // Calculate stats
            $total = $attendances->count();
            $terlambat = $attendances->where('status', 'terlambat')->count();
            $hadir = $attendances->where('status', 'hadir')->count();
            
            $lateNames = $attendances->where('status', 'terlambat')
                ->map(fn($a) => $a->user->name . " (" . ($a->user->schoolClass->nama_kelas ?? 'Tanpa Kelas') . ")")
                ->implode(', ');

            // Prepare Prompt
            $prompt = "Berikut adalah data absensi hari ini (" . $today->format('d M Y') . "):\n";
            $prompt .= "- Total hadir: {$total}\n";
            $prompt .= "- Tepat waktu: {$hadir}\n";
            $prompt .= "- Terlambat: {$terlambat}\n";
            
            if ($terlambat > 0) {
                $prompt .= "- Daftar siswa terlambat: {$lateNames}\n";
            }

            $prompt .= "\nMohon berikan ringkasan profesional dalam 2 paragraf terkait tingkat kedisiplinan hari ini dan berikan saran singkat untuk manajemen sekolah.";

            // Call Gemini API
            $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gemini API Key belum dikonfigurasi di .env'
                ], 500);
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->failed()) {
                throw new Exception("Gemini API Error: " . ($response->json()['error']['message'] ?? 'Unknown Error'));
            }

            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal menghasilkan insight.';

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $aiText,
                    'stats' => [
                        'total' => $total,
                        'terlambat' => $terlambat,
                        'hadir' => $hadir
                    ]
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
