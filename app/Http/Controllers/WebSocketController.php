<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

class WebSocketController extends Controller
{
    public function authorizeChannel(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $channelName = $request->channel_name;
        $user = Auth::user();
        
        Log::info('WebSocket Authorization Debug', [
            'user_id' => $user->id,
            'channel_name' => $channelName,
            'authenticated_user_id' => $user->id
        ]);

        // Remove 'private-' prefix if present
        $channelName = str_replace('private-', '', $channelName);

        // Check channel authorization based on channel name
        $channelParts = explode('.', $channelName);
        if (count($channelParts) !== 2) {
            return response()->json(['message' => 'Invalid channel format'], 403);
        }

        [$channelType, $channelId] = $channelParts;
        $channelId = (int) $channelId;

        // Handle different channel types
        switch ($channelType) {
            case 'user':
                // User can only subscribe to their own channel
                if ($channelId !== $user->id) {
                    return response()->json([
                        'message' => 'Unauthorized',
                        'debug' => [
                            'user_id' => $user->id,
                            'channel_id' => $channelId
                        ]
                    ], 403);
                }
                break;

            case 'city':
                // Check if user has access to this city
                if (!$user->cities()->where('id', $channelId)->exists()) {
                    return response()->json([
                        'message' => 'Unauthorized',
                        'debug' => [
                            'user_id' => $user->id,
                            'city_id' => $channelId
                        ]
                    ], 403);
                }
                break;

            default:
                return response()->json(['message' => 'Invalid channel type'], 403);
        }

        // Use Laravel's built-in broadcasting authorization
        $authData = Broadcast::auth($request);
        
        Log::info('Broadcast Auth Response', [
            'response' => $authData
        ]);

        return response()->json($authData);
    }
} 