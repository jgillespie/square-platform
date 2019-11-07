<?php

namespace App\Modules\Core\Http\Controllers\Frontend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Show the form for editing the account settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('core::frontend.settings.account');
    }

    /**
     * Update the account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('accounts')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $userEmailChanged = $user->email !== $validatedData['email'];

        $user->email = $validatedData['email'];
        if ($userEmailChanged) {
            $user->email_verified_at = null;
        }
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        if ($userEmailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return back()
            ->with('status', __('Your account has been updated.'));
    }
}
