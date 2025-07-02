<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EegData;
use Illuminate\Support\Facades\Storage;

class EegController extends Controller
{
    /**
     * Upload and store EEG data
     */
    public function upload(Request $request)
    {
        $request->validate([
            'eeg_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('eeg_file');
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

            return response()->json([
                'message' => 'EEG data uploaded successfully',
                'records_count' => count($eegRecords)
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
}
