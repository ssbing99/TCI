<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Mail\WelcomeMail;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Models\TeacherProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\TeacherRegisterRequest;

class TeacherRegisterController extends Controller
{
    /**
     * Show the application teacher registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTeacherRegistrationForm()
    {
        return view('frontend.auth.registerTeacher');
    }

    /**
     * Register new teacher
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    public function register(TeacherRegisterRequest $request)
    {
        $user = User::create($request->all());
        $user->confirmed = 1;
        if ($request->has('image')) {
            $file = $request->file('image');
            $user->avatar_type = 'storage';
            $user->avatar_location = $file->store('/avatars', 'public');
        }
        $user->active = 0;
        $user->save();
        $user->assignRole('teacher');
        $payment_details = [
            'bank_name' => request()->bank_name,
            'ifsc_code' => request()->ifsc_code,
            'account_number' => request()->account_number,
            'account_name' => request()->account_name,
            'paypal_email' => request()->paypal_email,
        ];
        $data = [
            'user_id' => $user->id,
            'facebook_link' => request()->facebook_link,
            'twitter_link' => request()->twitter_link,
            'linkedin_link' => request()->linkedin_link,
            'payment_method' => request()->payment_method,
            'payment_details' => json_encode($payment_details),
            'description'       => request()->description,
        ];
        TeacherProfile::create($data);

        if(isset($user->id)) {
            try {
                \Mail::to($user->email)->queue(new WelcomeMail($user, true));
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
            }
        }

        return redirect()->route('frontend.index')->withFlashSuccess(trans('labels.frontend.modal.registration_message'))->with(['openModel' => true]);
    }

}
