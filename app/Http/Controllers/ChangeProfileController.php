<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateNameChange;

class ChangeProfileController extends Controller
{
    public function showChangeProfileForm()
    {
        return view('profile.change');
    }

    public function changeProfile(CreateNameChange $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $user->name = $request->name;
        $user->save();
        return back()->with('flash_message', 'お名前を変更しました');
    }
}
