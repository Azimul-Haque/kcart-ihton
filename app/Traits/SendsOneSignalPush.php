<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SendsOneSignalPush
{
    /**
     * 🔔 OneSignal REST API Call Helper
     */
    protected function sendOneSignalPush($onesignalId, $title, $message)
    {
        try {
            Http::withHeaders([
                'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key', env('ONESIGNAL_REST_API_KEY')),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id'             => config('services.onesignal.app_id', env('ONESIGNAL_APP_ID')),
                'include_player_ids' => [$onesignalId],
                'headings'           => ['en' => $title],
                'contents'           => ['en' => $message],
            ]);
        } catch (\Exception $e) {
            Log::error("OneSignal Push Error: " . $e->getMessage());
        }
    }
}