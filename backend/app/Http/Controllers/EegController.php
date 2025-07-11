<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EegData;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Http;

class EegController extends Controller
{
    /**
     * Upload and store EEG data
     */
    public function upload(Request $request)
    {
        $request->validate([
            'eeg' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('eeg');
            $user = $request->user();

            // Read CSV file
            $csvData = array_map('str_getcsv', file($file->getPathname()));
            $headers = array_shift($csvData); // Remove header row

            $eegRecords = [];

            foreach ($csvData as $row) {
                if (count($row) >= 5) { // Ensure we have at least alpha, beta, gamma, theta, delta
                    $eegRecords[] = [
                        'user_id' => $user->id,
                        'alpha' => (float) $row[0] ?? 0,
                        'beta' => (float) $row[1] ?? 0,
                        'gamma' => (float) $row[2] ?? 0,
                        'theta' => (float) $row[3] ?? 0,
                        'delta' => (float) $row[4] ?? 0,
                        'timestamp' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (empty($eegRecords)) {
                return response()->json([
                    'message' => 'No valid EEG data found in the file'
                ], 400);
            }

            // Store EEG data in batches
            EegData::insert($eegRecords);

            // Get the latest EEG record for this user
            $latestEeg = EegData::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Call the ML service
            $mlResponse = Http::post('http://127.0.0.1:5000/predict', [
                'eeg_data' => [
                    'alpha' => $latestEeg->alpha,
                    'beta' => $latestEeg->beta,
                    'gamma' => $latestEeg->gamma,
                    'theta' => $latestEeg->theta,
                    'delta' => $latestEeg->delta,
                ]
            ]);

            if ($mlResponse->failed()) {
                return response()->json([
                    'message' => 'EEG data uploaded, but ML service failed',
                    'ml_error' => $mlResponse->body()
                ], 202);
            }

            $mlData = $mlResponse->json();

            // Store mood and personality in user_profiles
            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'mood' => $mlData['mood']['prediction'] ?? null,
                    'personality' => $mlData['personality']['traits'] ?? null,
                ]
            );

            return response()->json([
                'message' => 'EEG data uploaded and analyzed successfully',
                'records_count' => count($eegRecords),
                'mood' => $mlData['mood']['prediction'] ?? null,
                'personality' => $mlData['personality']['traits'] ?? null
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing EEG file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's EEG data
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $eegData = EegData::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(100) // Limit to last 100 records
            ->get();

        return response()->json([
            'eeg_data' => $eegData,
            'total_records' => $eegData->count()
        ]);
    }

    /**
     * Get latest EEG data for user
     */
    public function latest(Request $request)
    {
        $user = $request->user();

        $latestEeg = EegData::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestEeg) {
            return response()->json([
                'message' => 'No EEG data found for this user'
            ], 404);
        }

        return response()->json([
            'latest_eeg' => $latestEeg
        ]);
    }

    /**
     * Get user's latest mood and personality
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json([
                'message' => 'No profile data found for this user'
            ], 404);
        }
        return response()->json([
            'mood' => $profile->mood,
            'personality' => $profile->personality
        ]);
    }

    /**
     * Get random user matches (demo)
     */
    public function match(Request $request)
    {
        $user = $request->user();
        $matches = \App\Models\User::where('id', '!=', $user->id)
            ->inRandomOrder()
            ->limit(3)
            ->get()
            ->map(function($u) {
                $profile = \App\Models\UserProfile::where('user_id', $u->id)->first();
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'mood' => $profile->mood ?? null,
                    'personality' => $profile->personality ?? null,
                ];
            });
        return response()->json(['matches' => $matches]);
    }
}
