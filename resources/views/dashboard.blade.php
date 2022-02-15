<x-app-layout>
    <div id="app"></div>

    <div class="container footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-app-layout>
