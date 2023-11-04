<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Archipelago;
use App\Models\City;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $this->createCity($user);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    private function createCity($user): void
    {
        $archipelago = Archipelago::create();

        City::factory(1)->create([
            'user_id'        => $user->id,
            'archipelago_id' => $archipelago->id,
            'coord_x'        => 3,
            'coord_y'        => 3,
            'title'          => 'Player Island ' . $user->id
        ]);

        City::factory(1)->create([
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.PIRATE_BAY'),
            'user_id'            => null,
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 5,
            'coord_y'            => 4,
            'title'              => 'Pirate Bay'
        ]);
    }
}
