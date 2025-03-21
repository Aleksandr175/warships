import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
    }
}

class WebSocketService {
    private static instance: WebSocketService;
    private echo: Echo | null = null;
    private currentUserId: number | null = null;
    private subscriptions: { [key: string]: any } = {};

    private constructor() {
        // Initialize Pusher
        window.Pusher = Pusher;
    }

    public static getInstance(): WebSocketService {
        if (!WebSocketService.instance) {
            WebSocketService.instance = new WebSocketService();
        }
        return WebSocketService.instance;
    }

    public initialize(userId: number): void {
        this.currentUserId = userId;

        // Initialize Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.VITE_PUSHER_APP_KEY,
            cluster: process.env.VITE_PUSHER_APP_CLUSTER,
            wsHost: process.env.VITE_PUSHER_HOST ?? `ws-${process.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
            wsPort: process.env.VITE_PUSHER_PORT ?? 80,
            wssPort: process.env.VITE_PUSHER_PORT ?? 443,
            forceTLS: (process.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
            authEndpoint: '/broadcasting/auth',
        });

        this.echo = window.Echo;
    }

    public subscribeToUserUpdates(callback: (data: any) => void): void {
        if (!this.echo || !this.currentUserId) {
            console.error('WebSocket not initialized or user not set');
            return;
        }

        const channelName = `private-user.${this.currentUserId}`;
        
        // Unsubscribe if already subscribed
        if (this.subscriptions[channelName]) {
            this.unsubscribeFromUserUpdates();
        }

        // Subscribe to the channel
        this.subscriptions[channelName] = this.echo.private(channelName)
            .listen('WarshipBuilt', (data: any) => {
                console.log('Warship built:', data);
                callback(data);
            })
            .listen('BuildingBuilt', (data: any) => {
                console.log('Building built:', data);
                callback(data);
            })
            .listen('ResearchCompleted', (data: any) => {
                console.log('Research completed:', data);
                callback(data);
            });
    }

    public unsubscribeFromUserUpdates(): void {
        if (!this.echo || !this.currentUserId) return;

        const channelName = `private-user.${this.currentUserId}`;
        if (this.subscriptions[channelName]) {
            this.subscriptions[channelName].stopListening('WarshipBuilt');
            this.subscriptions[channelName].stopListening('BuildingBuilt');
            this.subscriptions[channelName].stopListening('ResearchCompleted');
            delete this.subscriptions[channelName];
        }
    }

    public unsubscribeFromAll(): void {
        Object.keys(this.subscriptions).forEach(channelName => {
            if (this.subscriptions[channelName]) {
                this.subscriptions[channelName].stopListening();
                delete this.subscriptions[channelName];
            }
        });
    }
}

export default WebSocketService; 