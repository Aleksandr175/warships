<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebSocketController extends Controller
{
    public function authorizeChannel(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $channelName = $request->channel_name;
        $channelParts = explode('.', $channelName);
        
        if (count($channelParts) !== 2) {
            return response()->json(['message' => 'Invalid channel format'], 403);
        }

        [$channelType, $channelId] = $channelParts;
        $channelId = (int) $channelId;

        // Debug information
        $user = Auth::user();
        
        Log::info('WebSocket Authorization Debug', [
            'user_id' => $user->id,
            'channel_name' => $channelName,
            'channel_type' => $channelType,
            'channel_id' => $channelId,
            'authenticated_user_id' => $user->id
        ]);

        // Handle different channel types
        switch ($channelType) {
            case 'private-user':
                // User can only subscribe to their own channel
                $hasAccess = $channelId === $user->id;
                break;
                
            default:
                return response()->json(['message' => 'Invalid channel type'], 403);
        }
        
        if (!$hasAccess) {
            return response()->json([
                'message' => 'Unauthorized',
                'debug' => [
                    'user_id' => $user->id,
                    'channel_type' => $channelType,
                    'channel_id' => $channelId
                ]
            ], 403);
        }

        return response()->json([
            'channel_name' => $channelName,
            'auth' => $request->channel_name
        ]);
    }
} 