<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdoptionController extends Controller
{
    public function show(Adoption $adoption)
    {
        return view('adoptions.details', ['adoption' => $adoption]);
    }

    public function adopt(Adoption $adoption)
    {
        /*
        |-----------------------------------------------------------------------
        | Task 4 User, step 6. You should assing $adoption
        | The $adoption variable should be assigned to the authenticated user.
        | This is done using the adopted_by field from the user column in the database.
        |-----------------------------------------------------------------------
        */
        //
        abort_if(! Auth::user(), 403);
        abort_if($adoption->adopted_by, 403);
        $user = Auth::user();
        $adoption->adopted_by = $user->id;
        $adoption->save();

        return redirect()->home()->with('success', "Pet $adoption->name adopted successfully");
    }


    public function mine()
    {
        /*
        |-----------------------------------------------------------------------
        | Task 5 User, step 3.
        | You should assing the $adoptions variable with a list of all adopted by the logged user.
        |-----------------------------------------------------------------------
        */

        $adoptions = Adoption::where('adopted_by', auth()->user()->id)->get();

        return view('adoptions.list', ['adoptions' => $adoptions, 'header' => 'My Adoptions']);
    }
}
