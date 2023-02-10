<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $adoptions = Adoption::latest()->unadopted()->paginate();
        return view('adoptions.list', ['adoptions' => $adoptions, 'header' => 'Available for adoption']);
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        /*
        |-----------------------------------------------------------------------
        | Task 4 Guest, step 5. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */

        // validation
        $attributes = request()->validate([
            // tilføj måske 'exists' til email
            'email' => 'required|email',
            'password' => 'required'
        ]);
        // logging in the user
        if(auth()->attempt($attributes)){
            // redirect to the right page
            return redirect('/');
        }

        return back()->withErrors(['email' => 'Your provided credentials could not be verified.']);

    }

    public function register()
    {
        return view('register');
    }

    public function doRegister(Request $request)
    {
        /*
        |-----------------------------------------------------------------------
        | Task 3 Guest, step 5. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */
        // validation
            $attributes = request()->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
               'password-confirmation' => 'required'
            ]);

            $attributes['password'] = bcrypt($attributes['password']);

        $user = User::create($attributes);

        \auth()->login($user);

        return redirect('/');
    }

    public function logout()
    {
        /*
        |-----------------------------------------------------------------------
        | Task 2 User, step 3. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */
        \auth()->logout();

        return redirect('/');
    }
}
