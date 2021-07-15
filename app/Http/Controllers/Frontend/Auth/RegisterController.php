<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Helpers\Frontend\Auth\Socialite;
use App\Events\Frontend\Auth\UserRegistered;
use App\Mail\Frontend\Auth\AdminRegistered;
use App\Mail\WelcomeMail;
use App\Models\Auth\User;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ClosureValidationRule;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(home_route());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        abort_unless(config('access.registration'), 404);

//        return view('frontend.auth.register')
//            ->withSocialiteLinks((new Socialite)->getSocialLinks());
        return view('frontend.auth.register')
            ->withSocialiteLinks((new Socialite)->getSocialLinksForSignup());
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],[
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]);

        if ($validator->passes()) {
            // Store your user in database
            event(new Registered($user = $this->create($request->all())));

            try {
                Mail::to($request->email)->queue(new WelcomeMail($user, false));
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
            }
            return response(['success' => true]);

        }

        return response(['errors' => $validator->errors()]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
                $user->dob = isset($data['dob']) ? $data['dob'] : NULL ;
                $user->phone = isset($data['phone']) ? $data['phone'] : NULL ;
                $user->gender = isset($data['gender']) ? $data['gender'] : NULL;
                $user->address = isset($data['address']) ? $data['address'] : NULL;
                $user->city =  isset($data['city']) ? $data['city'] : NULL;
                $user->pincode = isset($data['pincode']) ? $data['pincode'] : NULL;
                $user->postal = isset($data['postal']) ? $data['postal'] : NULL;
                $user->state = isset($data['state']) ? $data['state'] : NULL;
                $user->country = isset($data['country']) ? $data['country'] : NULL;
                $user->save();

        $userForRole = User::find($user->id);
        $userForRole->confirmed = 1;
        $userForRole->save();
        $userForRole->assignRole('student');

        if(config('access.users.registration_mail')) {
            $this->sendAdminMail($user);
        }

        return $user;
    }


    protected function createDump()
    {
        $user = User::create([
            'first_name' => 'Student','last_name' => 'User','email' => 'student@tci.com','dob' => NULL,'phone' => '21321347','gender' => NULL,'address' => 'mumbai','city' => 'mumbai','postal' => '400002','state' => 'mumbai','country' => 'AL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2014-09-12','password' => Hash::make('student@tci.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'student','last_name' => 'admin','email' => 'student1@tci.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '','city' => '','postal' => '','state' => '','country' => '','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2014-09-15','password' => Hash::make('student1@tci.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Test','last_name' => 'Student','email' => 'rishi.rawat@wwindia.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Street','city' => 'locality','postal' => '445566','state' => 'region','country' => 'BS','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2014-11-28','password' => Hash::make('rishi.rawat@wwindia.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Rishi','last_name' => 'Rawat','email' => 'rawat.rishi00@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Sagar, Madhya Pradesh','city' => 'Indore,','postal' => '254786','state' => 'Madhya','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-01-14','password' => Hash::make('rawat.rishi00@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'cheenu','last_name' => 'tyagi','email' => 'cheenu.tyagi@mail.vinove.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Test','city' => 'test','postal' => '110043','state' => 'delhi','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-01-22','password' => Hash::make('cheenu.tyagi@mail.vinove.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vlad','last_name' => 'Sokhin','email' => 'vladislav.sokhin@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'PT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Mexico City','created_at' => '2015-03-22','password' => Hash::make('vladislav.sokhin@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kimberly','last_name' => 'Bryant','email' => 'kimberly.l.bryant@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'GA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-22','password' => Hash::make('kimberly.l.bryant@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nohemy ','last_name' => 'Adrian','email' => 'nadphoto@ymail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111','state' => 'c','country' => 'AT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-22','password' => Hash::make('nadphoto@ymail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Otto','last_name' => 'Grimm','email' => 'ottogrimm@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '11111','state' => 'c','country' => 'NA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-23','password' => Hash::make('ottogrimm@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Anne','last_name' => 'Salminen-Cesari','email' => 'anne.salminen75@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '11111','state' => 'c','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-23','password' => Hash::make('anne.salminen75@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Page','last_name' => 'Spencer','email' => 'pspencer@gci.net','dob' => NULL,'phone' => '19077641578','gender' => NULL,'address' => '13101 Lupine Rd','city' => 'Anchorage','postal' => '99516','state' => 'Alaska','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-23','password' => Hash::make('pspencer@gci.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Pearl','last_name' => 'Scott-Marten','email' => 'smokeandpowder@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-27','password' => Hash::make('smokeandpowder@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Cher','last_name' => 'Chen','email' => 'ccthurmeier@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-27','password' => Hash::make('ccthurmeier@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'test','last_name' => 'test','email' => 'aditi.patil@wwindia.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Pune','city' => 'Pune','postal' => '411011','state' => 'Maharashtra','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-03-28','password' => Hash::make('aditi.patil@wwindia.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Baryalai','last_name' => 'Khosha','email' => 'bakhosha@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '11111','state' => 'c','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Tehran','created_at' => '2015-03-31','password' => Hash::make('bakhosha@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bonnie','last_name' => 'Carrender','email' => 'carrender.bonnie@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('carrender.bonnie@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kara','last_name' => 'Hudgens','email' => 'kara0530@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3615 Lakecrest dr','city' => 'Knoxville ','postal' => '37920','state' => 'Tennessee','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('kara0530@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vineet','last_name' => 'Chhatwal','email' => 'vineet.chhatwal@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('vineet.chhatwal@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nicky','last_name' => 'Weber','email' => 'webern20@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('webern20@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kseniya','last_name' => 'Pudofeeva','email' => 'kseniyapudofeeva@gmail.com','dob' => NULL,'phone' => '33782231333','gender' => NULL,'address' => '49 rue Pergolese','city' => 'Paris','postal' => '75116','state' => 'Ile-de-France','country' => 'FR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('kseniyapudofeeva@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'JoAnne','last_name' => 'Powless','email' => 'powlesje@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('powlesje@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Paul','last_name' => 'Lavergne','email' => 'pllavergne@gmail.com','dob' => NULL,'phone' => '7879348997','gender' => NULL,'address' => '805 Ponce de Leon Ave Apt 1202','city' => 'San Juan','postal' => '907','state' => 'PR','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-04-12','password' => Hash::make('pllavergne@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sejuti','last_name' => 'Basu','email' => 'sejutibasu@yahoo.co.in','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '111111','state' => 'c','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'New Delhi','created_at' => '2015-04-13','password' => Hash::make('sejutibasu@yahoo.co.in')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Umberto','last_name' => 'Michelucci','email' => 'umberto.michelucci@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Birchlenstr. 25','city' => 'Dübendorf','postal' => '8600','state' => 'ZH','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Bern','created_at' => '2015-05-06','password' => Hash::make('umberto.michelucci@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rails','last_name' => 'rails','email' => 'rordev20@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'pune','city' => 'pune','postal' => '123456','state' => '123456','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-05-07','password' => Hash::make('rordev20@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ror','last_name' => 'ror','email' => 'patelrohit478@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'ror','city' => 'ror','postal' => '123456','state' => 'ror','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-05-07','password' => Hash::make('patelrohit478@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Reef','last_name' => 'Fakhoury','email' => 'reeffakhoury@gmail.com','dob' => NULL,'phone' => '4560169516','gender' => NULL,'address' => 'strandgade 8E','city' => 'Copenhagen','postal' => '1401','state' => 'Christianhavn','country' => 'DK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-05-08','password' => Hash::make('reeffakhoury@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Suzette','last_name' => 'Nguyen ','email' => 'suzngyn@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'War Horse St','city' => 'San Diego','postal' => '92129','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-05-09','password' => Hash::make('suzngyn@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Charles','last_name' => 'Berkeley','email' => 'ccb@verizon.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '1234567','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-05-21','password' => Hash::make('ccb@verizon.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ashlee','last_name' => 'Bixby ','email' => 'atbixby@yahoo.com','dob' => NULL,'phone' => '8324839960','gender' => NULL,'address' => '2507 white oak dr','city' => 'houston','postal' => '77009','state' => 'Texas','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-05-21','password' => Hash::make('atbixby@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ingrid','last_name' => 'Toner','email' => 'toner.ingrid@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '1234567','state' => 'c','country' => 'IE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-01','password' => Hash::make('toner.ingrid@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sukanta','last_name' => 'mondal','email' => 'sukanta.m72@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '15/a/1  sital  sarkar  lane','city' => 'mahesh ,  hooghly','postal' => '712202','state' => 'west  bengal','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-13','password' => Hash::make('sukanta.m72@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Carrie Ann','last_name' => 'Kouri','email' => 'carrieannimages@gmail.com','dob' => NULL,'phone' => '6367518231','gender' => NULL,'address' => 'a','city' => 'b','postal' => '63146','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-14','password' => Hash::make('carrieannimages@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Christine','last_name' => 'Davis','email' => 'cdavis366@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Viale Fratelli Casiraghi, NR 15','city' => 'Sesto San Giovanni','postal' => '20099','state' => 'Milan','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-14','password' => Hash::make('cdavis366@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Davina','last_name' => 'Choy','email' => 'davinachoy@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1 look Fung path, 11a, Shatin, nt, hong kong','city' => 'Shatin','postal' => '0','state' => 'New Territories ','country' => 'HK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-15','password' => Hash::make('davinachoy@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Julie','last_name' => 'Thornton','email' => 'thornton@pacific.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '9700 Gibson Lane','city' => 'Potter Valley','postal' => '95469','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-15','password' => Hash::make('thornton@pacific.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Amanda','last_name' => 'Shives','email' => 'manda9131@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '5 Hamilton CT','city' => 'Thomasville','postal' => '27360','state' => 'NC','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-16','password' => Hash::make('manda9131@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lisa','last_name' => 'Eighmy','email' => 'lrpfau8184@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '807 penn ave','city' => 'Atlanta','postal' => '30308','state' => '?','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-16','password' => Hash::make('lrpfau8184@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nicole','last_name' => 'English','email' => 'nicoleenglish@optusnet.com.au','dob' => NULL,'phone' => '','gender' => NULL,'address' => '43 Whitfield Parade','city' => 'Hammondville','postal' => '2170','state' => 'New South Wales','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-17','password' => Hash::make('nicoleenglish@optusnet.com.au')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Martin','last_name' => 'Shakeshaft','email' => 'martin@martinshakeshaft.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '38 Parker Street','city' => 'Leek','postal' => 'ST13 6LB','state' => 'Staffordshire','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-19','password' => Hash::make('martin@martinshakeshaft.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Pamela ','last_name' => 'Davis','email' => 'pam.ann.davis@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'PO Box 383','city' => 'Maine','postal' => '4004','state' => 'USA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-19','password' => Hash::make('pam.ann.davis@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ashley ','last_name' => 'Ogrodowski','email' => 'ashleyxox92@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '730 Tall Oaks Dr','city' => 'Brick','postal' => '8724','state' => 'Ocean County','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-19','password' => Hash::make('ashleyxox92@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bayu','last_name' => 'Pradana','email' => 'laksmapradana@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'BSD ','city' => 'javanese Indonesian','postal' => '15345','state' => 'Indonesia','country' => 'ID','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-20','password' => Hash::make('laksmapradana@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Deborah','last_name' => 'Espinosa','email' => 'despinosa@sameskyphoto.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '437 NE 72nd St.','city' => 'Seattle','postal' => '98115','state' => 'Washington','country' => 'US','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2015-06-22','password' => Hash::make('despinosa@sameskyphoto.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Antoinette','last_name' => 'Addison','email' => 'frogmat@mac.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1833 Fletcher Way','city' => 'Santa Ynez','postal' => '93460','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-22','password' => Hash::make('frogmat@mac.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ted','last_name' => 'Davis','email' => 'tedhdavisphotography@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '513 cottonwood dr','city' => 'colorado Springs','postal' => '80911','state' => 'CO','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-24','password' => Hash::make('tedhdavisphotography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Brett ','last_name' => 'Cordeau','email' => 'brettcordeau@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '309 Maple Ave W','city' => 'Vienna','postal' => '22180','state' => 'VA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-25','password' => Hash::make('brettcordeau@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sarah','last_name' => 'McGrory','email' => 'sarah@getforkedandfly.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2 Gumtree Place','city' => 'Bangalow','postal' => '2479','state' => 'Bangalow','country' => 'AU','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2015-06-26','password' => Hash::make('sarah@getforkedandfly.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sarah','last_name' => 'McGrory','email' => 'mcgrorys@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2 Gumtree Place','city' => 'Bangalow','postal' => '2479','state' => 'NSW','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-26','password' => Hash::make('mcgrorys@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'shuvo deep','last_name' => 'deep','email' => 'shuvo.deep@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '36,sarson road chittagong','city' => 'chottagomg','postal' => '4000','state' => 'hidu','country' => 'BD','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2015-06-27','password' => Hash::make('shuvo.deep@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mahfuz','last_name' => 'Haque','email' => 'mahfuz.ap@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '21/3, R. K. Mission road, 3rd lane, Gopibug.','city' => 'Asia','postal' => '1203','state' => 'Asia','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-27','password' => Hash::make('mahfuz.ap@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Md. Shehab','last_name' => 'Shehab','email' => 'fujicolor8515@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '130, South Kamapur','city' => 'kamalapur','postal' => '1217','state' => 'Islam (sunni)','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-28','password' => Hash::make('fujicolor8515@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jake','last_name' => 'Isham','email' => 'isham.photography@gmail.com','dob' => NULL,'phone' => '5415175185','gender' => NULL,'address' => '1917 30th Street','city' => 'Florence','postal' => '97439','state' => 'Oregon','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-28','password' => Hash::make('isham.photography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Gaurav','last_name' => 'Sinha','email' => 'gaurav10sinha@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Gurgaon','city' => 'Gurgaon','postal' => '122002','state' => 'Gurgaon','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-06-30','password' => Hash::make('gaurav10sinha@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Renata','last_name' => 'Keva','email' => 'renata.keva@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Peterburi tee 40-2','city' => 'Tallinn','postal' => '11411','state' => 'Harjumaa','country' => 'EE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-01','password' => Hash::make('renata.keva@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Michele','last_name' => 'Lewis','email' => 'michele.lewis@xtra.co.nz','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4/363 Karaka Bay Rd, Karaka Bay','city' => 'Wellington','postal' => '6022','state' => 'Wellington','country' => 'NZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-05','password' => Hash::make('michele.lewis@xtra.co.nz')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Alvin Francis','last_name' => 'Fok','email' => 'alvinfrancis70@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Blk 159 Hougang St 11 #12-27','city' => 'Singapore','postal' => '530159','state' => 'Singapore','country' => 'SG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-05','password' => Hash::make('alvinfrancis70@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kristrun','last_name' => 'Hjartar','email' => 'kristrun.hjartar@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abcdefg','city' => 'hijklmnop','postal' => '1234567','state' => 'adb','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-06','password' => Hash::make('kristrun.hjartar@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Soula','last_name' => 'Pefkaros','email' => 'soula.pefkaros@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abcdefghijk','city' => 'lmnopqrs','postal' => '1234567','state' => 'asd','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-06','password' => Hash::make('soula.pefkaros@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'tammy','last_name' => 'mccloud','email' => 'tammymccloud@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '16266 366 avenue','city' => 'cresbard','postal' => '57435','state' => 'dakota','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-06','password' => Hash::make('tammymccloud@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Troy','last_name' => 'Larson','email' => 'troylarsonak@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3440 Jerde Circle','city' => 'Anchorage','postal' => '99504','state' => 'Alaska','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-08','password' => Hash::make('troylarsonak@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'prasenjit','last_name' => 'sengupta','email' => 'psen1969@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a 501 , gateway tower , ','city' => 'vaishali','postal' => '201010','state' => 'Ghaziabad , up','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-13','password' => Hash::make('psen1969@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Md. Nahidul','last_name' => 'Islam','email' => 'nahidulislam2000@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Beside Munshi Plaza, momenbag, Konapara, Demra','city' => 'Bangladesh','postal' => '1362','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-14','password' => Hash::make('nahidulislam2000@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Felix','last_name' => 'Dones','email' => 'felixkdones@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '60 WILLIAM STREET','city' => 'New Britain','postal' => '6051','state' => 'Connecticut','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-14','password' => Hash::make('felixkdones@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Emily','last_name' => 'Teater','email' => 'emily@emtphoto.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'PO BOX 1693 ','city' => 'Maryland Heights','postal' => '63043','state' => 'Missouri','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-14','password' => Hash::make('emily@emtphoto.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Moya ','last_name' => 'Neilson','email' => 'moya@chbc.eu','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Clonmel House, Forster Way','city' => 'Swords','postal' => 'K67F2K3','state' => 'Co. Dublin','country' => 'IE','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2015-07-16','password' => Hash::make('moya@chbc.eu')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'raghda','last_name' => 'mekawy','email' => 'raghdamekawy81@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'lavizon street','city' => 'alexandrie','postal' => '24599','state' => 'bolkly','country' => 'EG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-16','password' => Hash::make('raghdamekawy81@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ed','last_name' => 'Plummer','email' => 'eplumm@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '419 Oliver Rd','city' => 'Sewickley, PA','postal' => '15143','state' => 'USA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-18','password' => Hash::make('eplumm@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mike','last_name' => 'Arnott','email' => 'mikearnott@iinet.net.au','dob' => NULL,'phone' => '','gender' => NULL,'address' => '50 Brockway Rd','city' => 'Roleystone','postal' => '6111','state' => 'WA','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-19','password' => Hash::make('mikearnott@iinet.net.au')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Juliana','last_name' => 'Viana','email' => 'madebyjuliana@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Duvivier 37 Copacabana','city' => 'Rio de Janeiro','postal' => '22020020','state' => 'Non-US/Non-Canadian','country' => 'BR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-20','password' => Hash::make('madebyjuliana@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Amanda','last_name' => 'Bouthillier','email' => 'amanda.brooke.b@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Padre Antonio Viera ','city' => 'Portel','postal' => '6840 0000','state' => 'Para','country' => 'BR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-21','password' => Hash::make('amanda.brooke.b@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ameena','last_name' => 'Gorton','email' => 'ameenagorton@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1 avenue Joliot Curie','city' => 'Noves','postal' => '13550','state' => 'Bouches-du-Rhône','country' => 'FR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-28','password' => Hash::make('ameenagorton@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Arwa','last_name' => 'Lootah','email' => 'arwa.lootah@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abcd','city' => 'efghi','postal' => '1234567','state' => 'jklman','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-29','password' => Hash::make('arwa.lootah@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mahdi','last_name' => 'Surosh','email' => 'mahdi.surosh@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Kut-e-Sangi, Kabul, Afghanistan','city' => 'Kabul','postal' => '93','state' => 'Kabul','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-07-30','password' => Hash::make('mahdi.surosh@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jim','last_name' => 'Kuhr','email' => 'jimkuhr@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7831 Modern Oasis Drive','city' => 'San Diego','postal' => '92108','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2015-08-04','password' => Hash::make('jimkuhr@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'leonelmeshok','last_name' => 'leo','email' => 'leonelmeshok@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'no.1c/15, south pragaram street, thiruvottiyur, chennai','city' => 'chennai','postal' => '600019','state' => 'indian','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-05','password' => Hash::make('leonelmeshok@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sidney','last_name' => 'Luckett','email' => 'sidney.luckett@gmail.com','dob' => NULL,'phone' => '27767922876','gender' => NULL,'address' => '4 Bokkemanskloof Rd','city' => 'Hout Bay','postal' => '7806','state' => 'Western Cape','country' => 'ZA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-11','password' => Hash::make('sidney.luckett@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Graham','last_name' => 'Judd','email' => 'grahamjudd@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7 New Baily Road','city' => 'Ramna','postal' => '1000','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-12','password' => Hash::make('grahamjudd@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Edie','last_name' => 'Clifford','email' => 'egclifford@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '66 Morton Rd','city' => 'Milton','postal' => '2186','state' => 'MA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-13','password' => Hash::make('egclifford@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Narina','last_name' => 'Harris','email' => 'narinakharris@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Lower Richmond Road','city' => 'London','postal' => 'SW151EX','state' => 'UK','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-15','password' => Hash::make('narinakharris@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bonnie','last_name' => 'Schwartz','email' => 'bonzphotography@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '198 Edgewood Road','city' => 'Asheville','postal' => '28804','state' => 'North Carolina','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-15','password' => Hash::make('bonzphotography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'srishti','last_name' => 'godbole','email' => 'gode92@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Sayani Road','city' => 'ravindranatya ','postal' => '400025','state' => 'Maharashtra','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-17','password' => Hash::make('gode92@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Rafe','last_name' => 'umar siddiqui','email' => 'rafe@digiinteracts.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'b 74','city' => 'delhi','postal' => '110096','state' => 'delhi','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-19','password' => Hash::make('rafe@digiinteracts.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rafe','last_name' => 'mohd','email' => 'mohammadrafe@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'rr','city' => 'delhi','postal' => '110092','state' => 'delhi','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-24','password' => Hash::make('mohammadrafe@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Iftekhar','last_name' => 'iftekhar','email' => 'iftekhar@digiinteracts.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Street no . 2','city' => 'Delhi','postal' => '110034','state' => 'Delhi','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-26','password' => Hash::make('iftekhar@digiinteracts.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Pia','last_name' => 'Vachha','email' => 'piavachha@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abc','city' => 'def','postal' => '1234567','state' => 'hij','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Amsterdam','created_at' => '2015-08-27','password' => Hash::make('piavachha@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Tom','last_name' => 'Abisamra','email' => 'tomabisamra@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abc','city' => 'def','postal' => '1234567','state' => 'hij','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-27','password' => Hash::make('tomabisamra@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Frances','last_name' => 'Bruchez','email' => 'fbruchez@mac.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abc','city' => 'def','postal' => '1234567','state' => 'ghi','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Vienna','created_at' => '2015-08-27','password' => Hash::make('fbruchez@mac.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mariella','last_name' => 'Candela','email' => 'mariellacandela@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abc','city' => 'def','postal' => '1234567','state' => 'ghi','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-27','password' => Hash::make('mariellacandela@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Michelle','last_name' => 'Wolschlager','email' => 'a_miasma@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '99 Rhode Island St','city' => 'San Francisco','postal' => '94501','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-08-27','password' => Hash::make('a_miasma@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kelly','last_name' => 'Sobczak','email' => 'ksobczak@ch2m.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abc','city' => 'def','postal' => '1234567','state' => 'ghi','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Pacific Time (US & Canada)','created_at' => '2015-08-27','password' => Hash::make('ksobczak@ch2m.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jessica','last_name' => 'Herscher','email' => 'jessica.herscher@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abc','city' => 'def','postal' => '1234567','state' => 'ghi','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Eastern Time (US & Canada)','created_at' => '2015-08-30','password' => Hash::make('jessica.herscher@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mike','last_name' => 'Thibodeaux','email' => 'mikethibodeaux@sbcglobal.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '255 South street','city' => 'Vernon','postal' => '6066','state' => 'CT','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-03','password' => Hash::make('mikethibodeaux@sbcglobal.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'David','last_name' => 'Gales','email' => 'dgales@mac.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '14917 Glasgow Ct','city' => 'Tampa','postal' => '3624','state' => 'FL','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-09','password' => Hash::make('dgales@mac.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Stuart','last_name' => 'McGetrick','email' => 'sjmcgetrick@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '872 Teal Drive','city' => 'Burlington','postal' => 'L7T2Y7','state' => 'Ontario','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-13','password' => Hash::make('sjmcgetrick@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Fatu','last_name' => 'Wesley','email' => 'fwesleyz2@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2572 treehouse dr','city' => 'Virginia','postal' => '22192','state' => 'North america','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-14','password' => Hash::make('fwesleyz2@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Maddie','last_name' => 'Om','email' => 'maddieom@hotmail.co.uk','dob' => NULL,'phone' => '','gender' => NULL,'address' => '5 Victoria Road','city' => 'Gillingham','postal' => 'SP8 4HY','state' => 'Dorset','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-20','password' => Hash::make('maddieom@hotmail.co.uk')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Farzana','last_name' => 'Wahidy','email' => 'adpro.apap.project@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abcd','city' => 'efgh','postal' => '1234567','state' => 'ijak','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Kabul','created_at' => '2015-09-25','password' => Hash::make('adpro.apap.project@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jafar','last_name' => ' Rahimi','email' => 'jafarrahimi2013@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abcd','city' => 'efgh','postal' => '1234567','state' => 'ijkl','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Kabul','created_at' => '2015-09-25','password' => Hash::make('jafarrahimi2013@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Najiba','last_name' => 'Noori','email' => 'najibanori91@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'abcd','city' => 'efgh','postal' => '1234567','state' => 'ijkl','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Kabul','created_at' => '2015-09-25','password' => Hash::make('najibanori91@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Linda','last_name' => 'Needham','email' => 'lcn115@yahoo.com','dob' => NULL,'phone' => '5022956282','gender' => NULL,'address' => '3115 Cottage Rake','city' => 'Jeffersonville','postal' => '47130','state' => 'Indiana','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-26','password' => Hash::make('lcn115@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ilka','last_name' => 'Boogaard','email' => 'me@itismyweb.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2135 Palomar Ave','city' => 'Ventura','postal' => '93001','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-27','password' => Hash::make('me@itismyweb.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kanokporn','last_name' => 'Sriplin','email' => 'powerpoom@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '32 Ladpraw 34 ,Junkasem, Jatujak , Bangkok','city' => 'Thailand','postal' => '10900','state' => 'Ladpraw','country' => 'TH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-29','password' => Hash::make('powerpoom@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Richard','last_name' => 'Gough','email' => 'chopsm@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Flat 37 Vanguard House, 70 Martello St','city' => 'Hackney','postal' => 'E8 3QQ','state' => 'London','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-09-30','password' => Hash::make('chopsm@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Julie','last_name' => 'Helfrich','email' => 'juliehelfrich@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1015 Mountain Home Road','city' => 'Woodside','postal' => '94062','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-10-04','password' => Hash::make('juliehelfrich@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Donya','last_name' => 'Raslan','email' => 'donyar@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'road 5218','city' => 'Budaya','postal' => '26335','state' => '-','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-10-06','password' => Hash::make('donyar@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sabina','last_name' => 'Banu','email' => 'sabpro9@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '203, Succoro Gardens, Succoro, Porvorim','city' => 'Goa','postal' => '403501','state' => 'Goa','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-10-08','password' => Hash::make('sabpro9@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jeffrey','last_name' => 'Stanaway','email' => 'jeffinlesotho@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '230 S 161st Ct, Unit D','city' => 'Seattle','postal' => '98148','state' => 'Washington','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-10-08','password' => Hash::make('jeffinlesotho@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sabrina','last_name' => 'Maniscalco','email' => 'maniscalco.sabrina@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Via Malpensata 8','city' => 'Riazzino','postal' => '6595','state' => 'Ticino','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-10-13','password' => Hash::make('maniscalco.sabrina@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Brian','last_name' => 'Eisenberg','email' => 'bri.eisenberg@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '420 East 79th Street','city' => 'New York','postal' => '10075','state' => 'NY','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-10-13','password' => Hash::make('bri.eisenberg@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'susan','last_name' => 'jordan','email' => 'sue@suejordanphotography.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2108 eton ct','city' => 'west chester','postal' => '19382','state' => 'pa','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-11-04','password' => Hash::make('sue@suejordanphotography.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'giulia','last_name' => 'macchia','email' => 'giulia778@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'villa1108 green community west ','city' => 'dubai ','postal' => '0','state' => 'dubai','country' => 'AE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-11-17','password' => Hash::make('giulia778@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit','last_name' => 'Sumit','email' => 'sumit@digiinteracts.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Delhi','city' => 'Delhi','postal' => '110085','state' => 'North','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-11-19','password' => Hash::make('sumit@digiinteracts.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Simone','last_name' => 'Werle','email' => 'swerle.amazon@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Lamontstraße 27','city' => 'Muenchen','postal' => '81679','state' => 'Bavaria','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-01','password' => Hash::make('swerle.amazon@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Livia','last_name' => 'Rojas','email' => 'livia.rojas@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '100 Monte Cresta Ave #204','city' => 'Alameda County','postal' => '94611','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-02','password' => Hash::make('livia.rojas@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ian','last_name' => 'Carr','email' => 'iankcarr82@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7214 Woolrich Rd.','city' => 'Louisville, KY','postal' => '40222','state' => 'South','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-07','password' => Hash::make('iankcarr82@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ana','last_name' => 'kekelia','email' => 'annakekelia@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Birdston Farm Cottage','city' => 'glasgow','postal' => 'G66 1RW','state' => 'glasgow','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-09','password' => Hash::make('annakekelia@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Emmanuel','last_name' => 'Obani','email' => 'imaobani@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Plot 617 Customs Quarters Phase 3','city' => 'Gwagwalada','postal' => '100009','state' => 'Abuja','country' => 'NG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-14','password' => Hash::make('imaobani@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kristy','last_name' => 'Adams','email' => 'kristy@adamsandcophoto.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2409 Saint Mary Drive','city' => 'Camp Lejeune','postal' => '28547','state' => 'North Carolina','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-20','password' => Hash::make('kristy@adamsandcophoto.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Eleanor','last_name' => 'Briccetti','email' => 'ebriccetti@comcast.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '8339 Terrace Dr','city' => 'El Cerrito','postal' => '94530','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-21','password' => Hash::make('ebriccetti@comcast.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'nabila','last_name' => 'moon','email' => 'savannaview@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '472 east 54th avenue','city' => 'vancouver','postal' => 'V5X1L4','state' => 'vancouver','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-25','password' => Hash::make('savannaview@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Adnan ','last_name' => 'Al Berunie','email' => 'adnan.al.berunie@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '5/8, Block E, Lalmatia','city' => 'bangladeshi','postal' => '1207','state' => 'Bangladesh','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-27','password' => Hash::make('adnan.al.berunie@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Caldwell','last_name' => 'Manners','email' => 'caldwell.manners@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Cra 58 #39-31, Barrancabermeja','city' => 'Barrio Versalles','postal' => 'NA','state' => 'Santander','country' => 'CO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2015-12-31','password' => Hash::make('caldwell.manners@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Steve','last_name' => 'Kraus','email' => 'steve.kraus19@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3062 Planters Mill Drive','city' => 'Dacula, GA','postal' => '30019','state' => 'Southeastern US','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-03','password' => Hash::make('steve.kraus19@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jayanta','last_name' => 'Bose','email' => 'jayantabose71@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Block A3, Flat 304, Indraprastha, VIP Road','city' => 'Kaikhali','postal' => '700052','state' => 'West Bengal','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-05','password' => Hash::make('jayantabose71@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Chuck','last_name' => 'Skipper','email' => 'cpskipper@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6565 S Abbott Rd','city' => 'Orchard Park','postal' => '14127','state' => 'N Y','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-06','password' => Hash::make('cpskipper@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'michelle','last_name' => 'gillard','email' => 'transamchick_78@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '757 henderson church road','city' => 'gray court','postal' => '29645','state' => 'usa','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-06','password' => Hash::make('transamchick_78@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Padraic','last_name' => 'Reid','email' => 'padraicreid@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '75 McKee Road','city' => 'Finglas, Dublin 11','postal' => 'D11VN52','state' => '75 McKee Road, Finglas, Dublin 11','country' => 'IE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-07','password' => Hash::make('padraicreid@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Riccardo','last_name' => 'Livorni','email' => 'riccardo.livorni@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Corso San Leonardo, 52','city' => 'Ortona','postal' => '66026','state' => 'Abruzzo','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-08','password' => Hash::make('riccardo.livorni@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Katherine','last_name' => 'McKee','email' => 'kstanley17@mac.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '208A Fontaine Circle','city' => 'Lexington','postal' => '40502','state' => 'Kencky','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-10','password' => Hash::make('kstanley17@mac.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'David','last_name' => 'Parsons','email' => 'david@mediasystemscompany.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1821 Dexter Street','city' => 'Austin - Texas','postal' => '78704','state' => '1821 Dexter Street','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-12','password' => Hash::make('david@mediasystemscompany.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'pardeep','last_name' => 'pardeep','email' => 'pardeepbeniwal84@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'delhi','city' => 'idnian','postal' => '123123','state' => '1`23','country' => 'AI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-22','password' => Hash::make('pardeepbeniwal84@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Indra ','last_name' => 'Pradhan','email' => 'ipradhan511@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'guwahati','city' => 'guwahati','postal' => '781001','state' => 'assam,guwahati','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-23','password' => Hash::make('ipradhan511@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'John-Ross','last_name' => 'Cubacub','email' => 'johnny@sent.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Fuhrberger Str. 9G','city' => 'Niedersachsen','postal' => '30625','state' => 'Hannover','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-23','password' => Hash::make('johnny@sent.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Neelam','last_name' => 'Soni','email' => 'neelam.s.o.n.i.019@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'fet,mody university,laxmangrah,siker ,rajasthan','city' => 'laxmangrah ,siker ,rajasthan','postal' => '332311','state' => 'rajasthan','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-27','password' => Hash::make('neelam.s.o.n.i.019@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'shubham','last_name' => 'deshmukh','email' => 'deshmukhshubham786@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'pardi road','city' => 'near bhawani mandir','postal' => '440035','state' => 'Hindu','country' => 'IN','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-01-31','password' => Hash::make('deshmukhshubham786@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sarah ','last_name' => 'cowan','email' => 'slcowan@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '5 wilby carr gdns cantley','city' => 'doncaster','postal' => 'DN4 6FH','state' => 'south yorkshire ','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-01-31','password' => Hash::make('slcowan@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Desiree','last_name' => 'Gentil','email' => 'dekipuff@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Calle Tucan 61, Urb Alcores del Golf, No. 302','city' => 'Marbella','postal' => '29660','state' => 'Malaga','country' => 'ES','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-05','password' => Hash::make('dekipuff@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dave','last_name' => 'Priestley','email' => 'dave@priestley.co.uk','dob' => NULL,'phone' => '','gender' => NULL,'address' => '12 Morton Road','city' => 'Great Totham, Maldon','postal' => 'CM9 8QB','state' => 'Essex','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-09','password' => Hash::make('dave@priestley.co.uk')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Daniel','last_name' => 'Cohn','email' => 'daniel@patroncapital.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '48 East End Road','city' => 'Finchley','postal' => 'N3 3QU','state' => 'Please select below','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-10','password' => Hash::make('daniel@patroncapital.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Justin','last_name' => 'Gingrich','email' => 'justitingich81@superrito.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '122234','state' => 'adc','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-11','password' => Hash::make('justitingich81@superrito.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kim','last_name' => 'Welter','email' => 'kim.welter@icloud.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Wenigerstrasse 27','city' => 'St. Gallen','postal' => '9011','state' => 'St. Gallen','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-11','password' => Hash::make('kim.welter@icloud.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Azad','last_name' => 'Azad','email' => 'd16photography@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'House-8, Road-3C, Sector-9, Uttara, Dhaka, Bangladesh','city' => 'Uttara','postal' => '1230','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-17','password' => Hash::make('d16photography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Johanna','last_name' => 'Alfaro arguedas','email' => 'johana18_06@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '22513 SE 329th St','city' => 'Black diamond ','postal' => '98010','state' => 'WA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-21','password' => Hash::make('johana18_06@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Justin','last_name' => 'Swensen','email' => 'swensenjustinc@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1000 N 200 E','city' => 'Lyman','postal' => '84749','state' => 'Utah','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-22','password' => Hash::make('swensenjustinc@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'MP','last_name' => 'Reeve','email' => 'mpreeve@alum.mit.edu','dob' => NULL,'phone' => '','gender' => NULL,'address' => '39 RICHFIELD RD','city' => 'Arlington','postal' => '2474','state' => 'Massachusetts','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-23','password' => Hash::make('mpreeve@alum.mit.edu')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mushfiqur Masum','last_name' => 'Bhuiyan','email' => 'mushfiqur.rahman.masum@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '36 No. Ward, Gazipur City','city' => 'Bangladeshi','postal' => '1704','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-23','password' => Hash::make('mushfiqur.rahman.masum@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'imane','last_name' => 'idmouh','email' => 'idmouhimane.48@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'lgouira street 6eme tranche ','city' => 'casablanca','postal' => '20450','state' => 'casablanca','country' => 'MA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-02-27','password' => Hash::make('idmouhimane.48@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dyuti','last_name' => 'Bhattacharyya','email' => 'dyuti1999@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'biren roy road west','city' => 'kolkata','postal' => '700061','state' => 'sarsuna','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-03-09','password' => Hash::make('dyuti1999@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Soha','last_name' => 'ALTERKAIT','email' => 'suhatuha@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Block 3 Street 314 House 21','city' => 'Kuwait','postal' => '0','state' => 'Kuwait','country' => 'KW','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-03-10','password' => Hash::make('suhatuha@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'khiamal','last_name' => 'sahara','email' => 'saharamagic@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '34 rue ','city' => 'marrakech','postal' => '40010','state' => 'marrakech','country' => 'MA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-03-16','password' => Hash::make('saharamagic@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nichole','last_name' => 'Brunk','email' => 'nichole_alynn@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '539 E Duncan Ave','city' => 'Alexandria','postal' => '22301','state' => 'VA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-03-19','password' => Hash::make('nichole_alynn@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Clarissa','last_name' => 'Shantz','email' => 'clarissashantz480@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '15815 Belmont Dr.','city' => 'Big Rapids','postal' => '49307','state' => 'Michigan','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-03-24','password' => Hash::make('clarissashantz480@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Renjith','last_name' => 'Raj','email' => 'renjithofficalac@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Pala','city' => 'Kottayam','postal' => '686575','state' => 'Pala','country' => 'IN','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-03-29','password' => Hash::make('renjithofficalac@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit','last_name' => 'Mahto','email' => 'sumitmca11@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'India','city' => 'Delhi','postal' => '110085','state' => 'North','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-03-29','password' => Hash::make('sumitmca11@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'jaskaransingh','last_name' => 'singh','email' => 'jaskaran7809@yopmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'address','city' => 'locality','postal' => '140901','state' => 'reggion','country' => 'AR','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-04-20','password' => Hash::make('jaskaran7809@yopmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'jaskaran','last_name' => 'singh','email' => 'jaskaran1040@yopmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '140901','city' => 'chandigarh','postal' => '140901','state' => 'chandigarh','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-04-20','password' => Hash::make('jaskaran1040@yopmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Richard','last_name' => 'Cohen','email' => 'richardc@ane.ae','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Villa 28, street 57','city' => 'Satwa','postal' => '32321','state' => 'Dubai','country' => 'AE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-04-22','password' => Hash::make('richardc@ane.ae')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Virginia','last_name' => 'Stadius','email' => 'stadiusvirgi@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'larrea 1242','city' => 'Capital federal','postal' => 'NOSE','state' => 'Buenos Aires','country' => 'AR','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-04-25','password' => Hash::make('stadiusvirgi@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Shazia','last_name' => 'Razzaque','email' => 'shaziarazzaque@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '184 Kent Avenue','city' => 'Brooklyn','postal' => '11249','state' => 'New York','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-04-27','password' => Hash::make('shaziarazzaque@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nicholas','last_name' => 'Brudnicki','email' => 'nicholas.brudnicki@rogers.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3211 Wildflowers Court','city' => 'Mississauga','postal' => 'L5N 6V3','state' => 'Ontario','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-04-28','password' => Hash::make('nicholas.brudnicki@rogers.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lauren','last_name' => 'Blackwell','email' => 'redleash@satx.rr.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '15207 Fall Manor Dr','city' => 'San Antonio TX','postal' => '78247','state' => 'Southwest','country' => 'US','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-04-30','password' => Hash::make('redleash@satx.rr.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mehul','last_name' => 'Gulati','email' => 'mehulgulati@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3412 Tulane Drive','city' => 'Hyattsville','postal' => '20783','state' => 'MD','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-01','password' => Hash::make('mehulgulati@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'samin','last_name' => 'khademi','email' => 'sadaf1999.sk@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'daneshgah','city' => 'bnd','postal' => '13','state' => 'iran','country' => 'IR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-04','password' => Hash::make('sadaf1999.sk@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Samuel','last_name' => 'Maughn','email' => 'samuel@samuelmaughn.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '25 Nelson St Mocha Village','city' => 'East Bank Demerara','postal' => '592','state' => 'Region 4','country' => 'GY','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-05-05','password' => Hash::make('samuel@samuelmaughn.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Erin','last_name' => 'Ilisco','email' => 'erinchristine1130@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '501 S York Rd','city' => 'Hatboro','postal' => '19040','state' => 'Pennsylvania','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-11','password' => Hash::make('erinchristine1130@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Arunjeet','last_name' => 'Banerjee','email' => 'arunjeet@gmail.com','dob' => NULL,'phone' => '917408450888','gender' => NULL,'address' => '30, Tagore Town','city' => 'Allahabad','postal' => '211002','state' => 'Asia','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-11','password' => Hash::make('arunjeet@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Allison','last_name' => 'Bethea','email' => 'allison@fastmail.fm','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6429 Walther Ave - Unit C','city' => 'Baltimore','postal' => '21206','state' => 'Maryland','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-12','password' => Hash::make('allison@fastmail.fm')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Olivia ','last_name' => 'wilcox','email' => 'olivia@lecumberry.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '80 Essex Road','city' => 'North Kingstown','postal' => '2852','state' => 'North East','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-12','password' => Hash::make('olivia@lecumberry.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Elena','last_name' => 'DiGiovanni','email' => 'egdigio@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1881 Oak Ave Apt PH07W','city' => 'Evanston','postal' => '60201','state' => 'Illinois','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-15','password' => Hash::make('egdigio@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dolan','last_name' => 'Karmakar','email' => 'dolankarmakar27@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Mohakhali Wireless','city' => 'Dhaka','postal' => '1212','state' => 'South Asia','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-24','password' => Hash::make('dolankarmakar27@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'MD. Shiful Islam','last_name' => 'Ony','email' => 'bmbony91@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Agroni 9, londoni road, Subidbajar, Sylhet.','city' => 'Comilla','postal' => '3100','state' => 'Sylhet','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-24','password' => Hash::make('bmbony91@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bappi Islam','last_name' => 'Islam','email' => 'quay.bappi@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'House-24, Road-4, Housing Society, Nasirabad, Chittagong','city' => 'Bangladesh','postal' => '4203','state' => 'Chittagong','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-25','password' => Hash::make('quay.bappi@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kawsar','last_name' => 'Murad','email' => 'kosy786@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '101','city' => 'Derby Road','postal' => 'NN14JP','state' => 'Northampton','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-25','password' => Hash::make('kosy786@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Marie','last_name' => 'Wattier','email' => 'wattiersmx@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6420 Middle Ring Court','city' => 'Mobile','postal' => '36608','state' => 'AL','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-26','password' => Hash::make('wattiersmx@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Suvro Paul ','last_name' => 'Suvro ','email' => 'suvro_52@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Sylhet Bangladesh ','city' => 'Tuker Bazar Sylhet ','postal' => '3100','state' => 'Sylhet ','country' => 'BD','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-05-27','password' => Hash::make('suvro_52@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Meade','last_name' => 'McCoy','email' => 'me@meadoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4 NW 16th St','city' => 'Delray Beach','postal' => '33444','state' => 'Florida','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-30','password' => Hash::make('me@meadoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'abrar','last_name' => 'alyaqout','email' => 'abraralyaqout@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'kuwait','city' => 'kuwait','postal' => '965','state' => 'kuwait','country' => 'KW','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-30','password' => Hash::make('abraralyaqout@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit ','last_name' => 'Mahto','email' => 'contact@digiinteracts.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Delhi','city' => 'Delhiq','postal' => '110085','state' => 'Asia','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-30','password' => Hash::make('contact@digiinteracts.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ingrid','last_name' => 'Maier','email' => 'ingrid320@gmx.de','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ohlstadter Str. 8','city' => 'München','postal' => 'D 81373','state' => 'Bayern','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-30','password' => Hash::make('ingrid320@gmx.de')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'John','last_name' => 'Eastmond','email' => 'jeastmon@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1284 Lamplighter Way','city' => 'reston','postal' => '20194','state' => 'VA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-05-31','password' => Hash::make('jeastmon@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ana','last_name' => 'Costa','email' => 'aflpc@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'rua jose guilherme oliveira, 8','city' => 'Oeiras','postal' => '2780-336','state' => 'Oeiras','country' => 'PT','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-06-02','password' => Hash::make('aflpc@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Natalie','last_name' => 'Mayne','email' => 'nataliesnewmail@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Bin Mahmoud','city' => 'Doha','postal' => '0','state' => 'Doha','country' => 'QA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-06','password' => Hash::make('nataliesnewmail@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Katrina','last_name' => 'Morales','email' => 'katrina.morales@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '428 Elwood Avenue Apt 2S','city' => 'Hawthorne','postal' => '10532','state' => 'USA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-06','password' => Hash::make('katrina.morales@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'howtogetyourexback','last_name' => 'Amy','email' => 'jhonamy2011@yandex.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Hemawas Dham Pali, Rajesthan, India','city' => 'Hemawas Dham Pali, Rajesthan, India','postal' => '306401','state' => 'Rajesthan','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-07','password' => Hash::make('jhonamy2011@yandex.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Amna','last_name' => 'Altaf','email' => 'amna.altaf@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Bahria town','city' => 'Phase 8','postal' => '46220','state' => 'Rawalpindi','country' => 'PK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-09','password' => Hash::make('amna.altaf@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Eiliyah','last_name' => 'Hana','email' => 'eiliyah.hana@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a to g elites','city' => 'kuwait','postal' => '71651','state' => 'kuwait','country' => 'KW','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-11','password' => Hash::make('eiliyah.hana@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Shri','last_name' => 'Shinde','email' => 'grapeyard@rediffmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'asdf','city' => 'adf','postal' => '411045','state' => 'pune','country' => 'IN','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-06-11','password' => Hash::make('grapeyard@rediffmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jennifer','last_name' => 'Barnaby','email' => 'jennifer.barnaby.photo@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3 bis, rue de l\'Abbaye','city' => 'Monaco','postal' => '98000','state' => 'Europe','country' => 'MC','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-12','password' => Hash::make('jennifer.barnaby.photo@gmail.com')]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Biju','last_name' => 'Mathew','email' => 'net2netcare@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Kothanur, Bangalore','city' => 'Bangalore','postal' => '560077','state' => 'Karnataka','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-17','password' => Hash::make('net2netcare@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Osman','last_name' => 'Sharif','email' => 'msharif9@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '12718 W Old Baltimore Road','city' => 'Boyds','postal' => '20841','state' => 'Maryland','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-18','password' => Hash::make('msharif9@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Heather','last_name' => 'Ballinger','email' => 'hballinger07@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '165 Northside Dr','city' => 'Cynthiana','postal' => '41031','state' => 'KY','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-06-26','password' => Hash::make('hballinger07@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Christy','last_name' => 'Silverthorne','email' => 'christysilverthorne@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1223 Calvin Street','city' => 'Eugene','postal' => '97401','state' => 'Oregon','country' => 'US','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-06-29','password' => Hash::make('christysilverthorne@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Francesca ','last_name' => 'Russell','email' => 'francescarussell@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '278 Dorchester Road','city' => 'Garden City South','postal' => '11530','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-02','password' => Hash::make('francescarussell@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Darcy','last_name' => 'Pettigrew','email' => 'dpimarn@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1429 E Monte Cristo Ave','city' => 'Phoenix','postal' => '85022','state' => 'Arizona','country' => 'US','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-07-08','password' => Hash::make('dpimarn@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Darcy','last_name' => 'Pettigrew','email' => 'dpimarn@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1429 E Monte Cristo Ave','city' => 'Phoenix','postal' => '85022','state' => 'Arizona','country' => 'US','avatar_type' => 'storage','active' => '0','confirmed' => '0','timezone' => 'London','created_at' => '2016-07-08','password' => Hash::make('dpimarn@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Md. Kaoser','last_name' => 'Bin Siddique','email' => 'kaoserbd@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '12/6-B Tolarbag','city' => 'Mirpur','postal' => '1216','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-09','password' => Hash::make('kaoserbd@gmail.com')
        ]);
//        $user->save();
//        $user->assignRole('student');
//        $user = null;
//        $user = User::create([
//            'first_name' => 'Sumit','last_name' => 'Mahto','email' => 'sumitbecool87@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Gaja','city' => 'Hsndj','postal' => 'HAKJD','state' => 'Europe ','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-12','password' => Hash::make('sumitbecool87@gmail.com')
//        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Wojciech Bela','last_name' => 'Elbich','email' => 'w.elbich@web.de','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Waldmllerstr. 15','city' => 'München','postal' => '81479','state' => 'Bavaria','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-20','password' => Hash::make('w.elbich@web.de')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Zeina','last_name' => 'Barraj','email' => 'zbarraj@showonpro.com','dob' => NULL,'phone' => '9613724110','gender' => NULL,'address' => 'badaro, Beirut','city' => 'Lebanon','postal' => '2 0 3 8  3 0 5 4','state' => 'middle east','country' => 'LB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-21','password' => Hash::make('zbarraj@showonpro.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Meera','last_name' => 'Yoga','email' => 'meerayoga2001@yahoo.co.in','dob' => NULL,'phone' => '','gender' => NULL,'address' => '#6, 15th Cross Gayathridevi Park Extn','city' => 'Vyalikaval Bangalore','postal' => '560003','state' => 'Karnataka ','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-22','password' => Hash::make('meerayoga2001@yahoo.co.in')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'andrew','last_name' => 'blackwell','email' => 'andreweacompany@yahoo.co.uk','dob' => NULL,'phone' => '','gender' => NULL,'address' => '12','city' => 'english','postal' => 'WR12NG','state' => 'uk','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-24','password' => Hash::make('andreweacompany@yahoo.co.uk')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Donald Noriega','last_name' => 'Mr.','email' => 'donnoriega619@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '85 east san ysidro blavd.','city' => 'san ysidro california','postal' => '92173','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-25','password' => Hash::make('donnoriega619@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kristen','last_name' => 'Fichera','email' => 'k.lea@outlook.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '117 Oak Street','city' => 'Leesville','postal' => '71446','state' => 'Vernon','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-07-25','password' => Hash::make('k.lea@outlook.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Marian','last_name' => 'McClellan','email' => 'marianmcclellan@mac.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2585 NW 225','city' => 'Osceola','postal' => '64776','state' => 'Missouri','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-01','password' => Hash::make('marianmcclellan@mac.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Stine','last_name' => 'Bang Hansen','email' => 'stine.bang@live.dk','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'mysundegade 9','city' => 'Copenhagen','postal' => '1668','state' => 'Cpenhagen','country' => 'DK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-03','password' => Hash::make('stine.bang@live.dk')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Frances','last_name' => 'Schwabenland','email' => 'francesanne@msn.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7640 Forrest Avenue','city' => 'Philadelphia','postal' => '19150','state' => 'PA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-07','password' => Hash::make('francesanne@msn.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sabiha ','last_name' => 'Mohamad ','email' => 'sabiha410@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Karama, Dubai ','city' => 'Dubai , UAE','postal' => '12198 DUBAI','state' => 'Dubai ','country' => 'AE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-08','password' => Hash::make('sabiha410@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'BADER','last_name' => 'ALAWADHI','email' => 'bawadhi@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Obertorstrasse 100-102','city' => 'Bad Camberg','postal' => '65520','state' => 'Bad Camberg','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-14','password' => Hash::make('bawadhi@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Anthony','last_name' => 'Roumi','email' => 'imuorr@outlook.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Nefaa Street','city' => 'Dekwaneh','postal' => '961','state' => 'Beirut','country' => 'LB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-15','password' => Hash::make('imuorr@outlook.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'iván','last_name' => 'olivas','email' => 'ivanolopez@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'quinta mayor 15','city' => 'hermosillo','postal' => '83240','state' => 'quinta mayor 15','country' => 'MX','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-24','password' => Hash::make('ivanolopez@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Staša','last_name' => 'Kamplet','email' => 'stacy666@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ludvika Plambergerja 46','city' => 'Miklavž na Dravskem polju','postal' => '2204','state' => 'Maribor','country' => 'SI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-27','password' => Hash::make('stacy666@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Andrew','last_name' => 'Khoo','email' => 'zhoyee@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1962 29th Avenue','city' => 'San Francisco','postal' => '94116','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-08-28','password' => Hash::make('zhoyee@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Heather ','last_name' => 'Whitten','email' => 'whittenhm@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '122 w calle mantilla ','city' => 'sahuarita','postal' => '85629','state' => 'arizona','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-09-06','password' => Hash::make('whittenhm@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'nadine','last_name' => 'chamaa','email' => 'nourie.91@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ghadour el Saad','city' => 'Ashrafieh','postal' => '55511','state' => 'Beirut','country' => 'LB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-09-09','password' => Hash::make('nourie.91@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ingrid','last_name' => 'Halvorsen','email' => 'inhalv@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Frostaveien 7','city' => 'Trondheim','postal' => '7068','state' => 'Trondheim','country' => 'NO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-09-12','password' => Hash::make('inhalv@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'anisha','last_name' => 'raju','email' => 'anisha23186@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'dubai','city' => 'dubai','postal' => '6666','state' => 'dubai','country' => 'AE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-09-30','password' => Hash::make('anisha23186@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sowmya ','last_name' => 'Naik','email' => 'sowmyanaik2105@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Kulupwadi, Borivali East','city' => 'Raheja estate','postal' => '400066','state' => 'Maharashtra ','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-10-03','password' => Hash::make('sowmyanaik2105@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ikenna','last_name' => 'Enukora','email' => 'ikennaenukora@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4A Adeyemo Alakija Street ','city' => 'Victoria Island','postal' => '234','state' => 'Lagos','country' => 'NG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-10-10','password' => Hash::make('ikennaenukora@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Prohor','last_name' => 'Dorofeev','email' => 'gourikov@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Krasnobogatirskaya str.','city' => '9-122','postal' => '107564','state' => 'Moscow','country' => 'RU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-03','password' => Hash::make('gourikov@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mattia','last_name' => 'Spinelli','email' => 'mattia.spinelli3@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Josef-Loinger Straße 13','city' => 'Wörgl','postal' => '6300','state' => 'Tyrol','country' => 'AT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-03','password' => Hash::make('mattia.spinelli3@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Angela ','last_name' => 'Ramsey','email' => 'angelaramseyphoto@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6008 upper brandon place','city' => 'norfolk','postal' => '23508','state' => 'VA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-07','password' => Hash::make('angelaramseyphoto@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'kelly ','last_name' => 'mitchell','email' => 'ttosha.km@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'henry street','city' => 'port of spain ','postal' => '868','state' => 'port of spain ','country' => 'TT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-08','password' => Hash::make('ttosha.km@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Abdullah Qader ','last_name' => 'Sumon ','email' => 'sumon53@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Chittagong ','city' => 'Chittagong ','postal' => '4213','state' => 'Chittagong ','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('sumon53@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Syed','last_name' => 'Antu','email' => 'syedantu@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3/4 Asad avenue.  Mohammadpur','city' => 'Mohammadpur.','postal' => '1207','state' => ' Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('syedantu@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nahian','last_name' => 'BABU','email' => 'nieadalnahian@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Halishahar B-Block Uttora R/A house no 10','city' => 'Halishahar B-Block Uttora R/A house no 10 Chittagong','postal' => '4216','state' => 'Muslim ','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('nieadalnahian@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Foysal Ibn','last_name' => 'Anowar','email' => 'foysal.anowar@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Anandabug','city' => 'Chittagong ','postal' => '4000','state' => 'Chittagong','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('foysal.anowar@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumon','last_name' => 'Sumon','email' => 'kawsaratwork@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '295/zha/10, Talli Office Road, Sikder Real Estate, Zhigatola, Dhaka.','city' => 'Bangladeshi','postal' => '1209','state' => 'Dhaka-Comilla','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('kawsaratwork@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'waqas ali','last_name' => 'jinn','email' => 'waqasali4291@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'street no 1','city' => 'lahore','postal' => '5400','state' => 'lahore','country' => 'PK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('waqasali4291@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Asaduzzaman','last_name' => 'Shahed','email' => 'infocus500@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'North Tolatbag, Ansarcamp.','city' => 'Mirpur-1','postal' => '1216','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('infocus500@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Joy','last_name' => 'Banik','email' => 'ovrop009@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Faridabad','city' => 'Sutrapur','postal' => 'DHAKA-1204','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('ovrop009@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Joy','last_name' => 'Banik','email' => 'ovroo009@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Faridabad','city' => 'Sutrapur','postal' => '1204','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-11','password' => Hash::make('ovroo009@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Anik','last_name' => 'Sk Ali Alfaz','email' => 'alfaz143ulab@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '74/3 Haji Afsar Uddin Road','city' => 'Dhanmandi 15','postal' => '1209','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-12','password' => Hash::make('alfaz143ulab@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Humaira','last_name' => 'Humaira','email' => 'rubana22@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Flat#1403, Building#04, Japan garden City','city' => 'Mohammadpur','postal' => '1206','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-12','password' => Hash::make('rubana22@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jonathan ','last_name' => 'Moldover ','email' => 'jonathan.moldover.photo@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '26 Crescent St, apt 200','city' => 'Northampton ','postal' => '1060','state' => 'Massachusetts ','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-16','password' => Hash::make('jonathan.moldover.photo@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Neha','last_name' => 'Makhija','email' => 'neha181.makhija@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '346 Makhija Cottage 4th Main ','city' => 'Sadashivnagar Bangalore','postal' => '560080','state' => 'Karnataka','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-11-29','password' => Hash::make('neha181.makhija@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Liz','last_name' => 'Hale','email' => 'lizh@backroads.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'San Rafael','postal' => '65432','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-06','password' => Hash::make('lizh@backroads.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit','last_name' => 'Mahto','email' => 'digiinteracts@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'rohini','city' => 'Delhi','postal' => '110085','state' => 'Delhil','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-06','password' => Hash::make('digiinteracts@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'kennithbroun475','last_name' => 'Kennith','email' => 'suzie@d.gsasearchengineranker.site','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Im Wingert 24','city' => 'Im Wingert 24','postal' => 'IM WINGERT 24','state' => 'Im Wingert 24','country' => 'ZM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-08','password' => Hash::make('suzie@d.gsasearchengineranker.site')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'saiful amin kazal','last_name' => 'amin','email' => 'kazal1968@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'dhanmondi,dhaka','city' => 'dhaka','postal' => '1209','state' => 'dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-11','password' => Hash::make('kazal1968@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Peter','last_name' => 'Gee','email' => 'peter.gee@xtra.co.nz','dob' => NULL,'phone' => '','gender' => NULL,'address' => '9d Prebblewood Drive','city' => 'Prebbleton','postal' => '7604','state' => 'Canterbury','country' => 'NZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-14','password' => Hash::make('peter.gee@xtra.co.nz')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Anshul Raj','last_name' => 'Khurana','email' => 'anshulrajkurana@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'BW-104 D','city' => 'Shalimar Bagh','postal' => '110088','state' => 'Delhi','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-14','password' => Hash::make('anshulrajkurana@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sajjad','last_name' => 'Sarwar','email' => 'ssruss@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '10144 Boca Entrada Blvd','city' => '413','postal' => '33428','state' => 'USA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-16','password' => Hash::make('ssruss@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sue','last_name' => 'Butler','email' => 'susan@bluedorritt.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '8 Muscatel Ave','city' => 'Wattle Park','postal' => '5066','state' => 'South Australia','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-20','password' => Hash::make('susan@bluedorritt.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Adam','last_name' => 'Butler','email' => 'adam@80kms.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '99 Anzac Hwy','city' => 'Ashford','postal' => '5035','state' => 'South Australia','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-21','password' => Hash::make('adam@80kms.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'alene','last_name' => 'galin','email' => 'alene221@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '69 Stratton Forest Way','city' => 'Simsbury','postal' => '6070','state' => 'CT','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-26','password' => Hash::make('alene221@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'shishant sharma','last_name' => 'shveta','email' => 'lokhabit@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'block b11 flat 102','city' => 'fortune soumya heritage near ips school','postal' => '462026','state' => 'bhopal','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2016-12-30','password' => Hash::make('lokhabit@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nithiyanandam','last_name' => 'Shanmugam','email' => 'nithiyan.photography@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '8/18,Vaanamamalai Nagar','city' => 'Bye-Pass Road,Madurai','postal' => '625010','state' => 'Tamilnadhu','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-08','password' => Hash::make('nithiyan.photography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jennifer','last_name' => 'Arzonetti','email' => 'arzonetti@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '810 Oak street','city' => 'Roselle','postal' => '60172','state' => '810 Oak street','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-08','password' => Hash::make('arzonetti@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'kym','last_name' => 'ghee','email' => 'kymghee@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4051 w. 4th st','city' => 'los angeles, ca','postal' => '90020','state' => 'ca','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-09','password' => Hash::make('kymghee@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sue','last_name' => 'Perse','email' => 'sueperse@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '547 Summit Drive','city' => 'West Bend, WI','postal' => '53095','state' => 'midwestern USA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-10','password' => Hash::make('sueperse@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rita manuel','last_name' => 'rita manuel','email' => 'rkodak@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '221 third ave,port arthur','city' => 'texas','postal' => '77642','state' => 'texas','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-11','password' => Hash::make('rkodak@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'buckeverson107','last_name' => 'Buck','email' => 'bryon@c.gsasearchengineranker.pw','dob' => NULL,'phone' => '','gender' => NULL,'address' => '65 Southend Avenue','city' => '65 Southend Avenue','postal' => '65 SOUTHEND AVENUE','state' => '65 Southend Avenue','country' => 'LY','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-13','password' => Hash::make('bryon@c.gsasearchengineranker.pw')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'elmohuerta6005','last_name' => 'Elmo','email' => 'lyman@c.gsasearchengineranker.pw','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Betburweg 94','city' => 'Betburweg 94','postal' => 'BETBURWEG 94','state' => 'Betburweg 94','country' => 'DJ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-14','password' => Hash::make('lyman@c.gsasearchengineranker.pw')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'janniemartinelli','last_name' => 'Jannie','email' => 'allan@b.gsasearchengineranker.space','dob' => NULL,'phone' => '','gender' => NULL,'address' => '34 Crescent Avenue','city' => '34 Crescent Avenue','postal' => '34 CRESCENT AVENUE','state' => '34 Crescent Avenue','country' => 'PG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-16','password' => Hash::make('allan@b.gsasearchengineranker.space')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Karen','last_name' => 'Vohs','email' => 'kvohs@bellsouth.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '835 Shoreland Rd','city' => 'Winston-Salem','postal' => '27106','state' => 'North Carolina','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-22','password' => Hash::make('kvohs@bellsouth.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bikram ','last_name' => 'Sarkar','email' => 'bikramsarkar605@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '48 nripendrapally','city' => 'south santinagar','postal' => '711227','state' => 'west bengal','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-23','password' => Hash::make('bikramsarkar605@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'brandenerlikilyi','last_name' => 'Branden','email' => 'trudi@a.gsasearchengineranker.space','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Rua Tutoia 1187','city' => 'Rua Tutoia 1187','postal' => 'RUA TUTOIA 1187','state' => 'Rua Tutoia 1187','country' => 'AG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-25','password' => Hash::make('trudi@a.gsasearchengineranker.space')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Enikő','last_name' => 'Szathmáry','email' => 'szateni67@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Diós árok 49/a','city' => 'Budapest','postal' => '1125','state' => 'Budapest','country' => 'HU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-27','password' => Hash::make('szateni67@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'morabazile0','last_name' => 'Mora','email' => 'dotty@e.ps4.rocks','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ettensestraat 62','city' => 'Ettensestraat 62','postal' => 'ETTENSESTRAAT 62','state' => 'Ettensestraat 62','country' => 'FJ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-01-31','password' => Hash::make('dotty@e.ps4.rocks')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Frances','last_name' => 'Dart','email' => 'frances.dart@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '16 Murchison Street','city' => 'Kaleen','postal' => '2617','state' => 'ACT','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-01','password' => Hash::make('frances.dart@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'delilahricci4','last_name' => 'Delilah','email' => 'carlena@f.gsasearchengineranker.site','dob' => NULL,'phone' => '','gender' => NULL,'address' => '76 Scotsburn Rd','city' => '76 Scotsburn Rd','postal' => '76 SCOTSBURN RD','state' => '76 Scotsburn Rd','country' => 'SD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-01','password' => Hash::make('carlena@f.gsasearchengineranker.site')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Larry','last_name' => 'Felton','email' => 'larry@larryfelton.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2 Bryn Mawr Ct','city' => 'Albany ','postal' => '12211','state' => 'NY','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-04','password' => Hash::make('larry@larryfelton.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'gilbertokvz','last_name' => 'Gilberto','email' => 'oswaldo@b.gsasearchengineranker.top','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4714 South Street','city' => '4714 South Street','postal' => '4714 SOUTH STREET','state' => '4714 South Street','country' => 'CR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-06','password' => Hash::make('oswaldo@b.gsasearchengineranker.top')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jyoti','last_name' => 'J','email' => 'jo@princeworldwide.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Dubai','city' => 'Dubai','postal' => '0','state' => 'ME','country' => 'AE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-08','password' => Hash::make('jo@princeworldwide.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Paz','last_name' => 'Calaguian','email' => 'topazprod.uae@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Downtown Dubai','city' => 'Dubai','postal' => '233093','state' => 'Dubai','country' => 'AE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-09','password' => Hash::make('topazprod.uae@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ebonygolding','last_name' => 'Ebony','email' => 'dominick@d.gsasearchengineranker.top','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3537 Roy Alley','city' => '3537 Roy Alley','postal' => '3537 ROY ALLEY','state' => '3537 Roy Alley','country' => 'TV','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-11','password' => Hash::make('dominick@d.gsasearchengineranker.top')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'myrongeiger56','last_name' => 'Myron','email' => 'jame@f.gsasearchengineranker.top','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Grolmanstra?E 57','city' => 'Grolmanstra?E 57','postal' => 'GROLMANSTRA?E 57','state' => 'Grolmanstra?E 57','country' => 'TH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-15','password' => Hash::make('jame@f.gsasearchengineranker.top')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Swapnil','last_name' => 'Akash','email' => 'akash.newkash@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'West Agargaon, Shere-E-Bangla','city' => 'Dhaka','postal' => '1207','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-15','password' => Hash::make('akash.newkash@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'bryoncintron','last_name' => 'Bryon','email' => 'latonia@a.gsasearchengineranker.space','dob' => NULL,'phone' => '','gender' => NULL,'address' => '69 Bridge Street','city' => '69 Bridge Street','postal' => '69 BRIDGE STREET','state' => '69 Bridge Street','country' => 'VU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-22','password' => Hash::make('latonia@a.gsasearchengineranker.space')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'nahla','last_name' => 'dulaijan','email' => 'omsaudg@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'jubail','city' => 'saudi arabia','postal' => '11111','state' => 'middle  east','country' => 'SA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-02-28','password' => Hash::make('omsaudg@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'MD SHAFAAT ULLAH','last_name' => 'HIMEL','email' => 'shafaat714@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Sobhanbag, Dhanmondi','city' => 'Dhaka','postal' => '1207','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-03-01','password' => Hash::make('shafaat714@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mhmd Ali M ','last_name' => 'Hakmi','email' => 'alhkmi10100@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'kimball','city' => 'cc','postal' => '60625','state' => 'chicago','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-03-02','password' => Hash::make('alhkmi10100@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lucia','last_name' => 'Vazquez','email' => 'luliv@live.com.ar','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ambrosetti','city' => 'Ciudad de Buenos Aires','postal' => '1405','state' => 'Ciudad de Buenos Aires','country' => 'AR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-03-05','password' => Hash::make('luliv@live.com.ar')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jen','last_name' => 'Antill','email' => 'jenleighantill@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'PO BOX 6402','city' => 'Santa Fe','postal' => '87502','state' => 'New Mexico','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-03-15','password' => Hash::make('jenleighantill@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Michelle','last_name' => 'Anselmo','email' => 'chicagofrogs@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2280 Demington Drive','city' => 'Cleveland Heights','postal' => '44106','state' => 'Ohio','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-03-30','password' => Hash::make('chicagofrogs@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Laurie Karine','last_name' => 'van Dam','email' => 'info@lauriekarine.nl','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Havendam ','city' => 'Oud-Beijerland','postal' => '3262 AE','state' => 'Zuid-Holland','country' => 'NL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-03-31','password' => Hash::make('info@lauriekarine.nl')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sarah ','last_name' => 'Nichols','email' => 'ssnichols313@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '31531A Krieger Ln','city' => 'Lillian','postal' => '36549','state' => 'Alabama','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-04-01','password' => Hash::make('ssnichols313@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bev','last_name' => 'Jay','email' => 'bgjay5@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '910 Apukwa Drive','city' => 'Manitou Beach','postal' => 'S0K 4T1','state' => 'Saskatchewan','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-04-09','password' => Hash::make('bgjay5@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Edson','last_name' => 'Azevedo','email' => 'edson@eazevedo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Via triulziana','city' => 'San Donato Milanese','postal' => '20097','state' => 'Milan','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-04-14','password' => Hash::make('edson@eazevedo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nick','last_name' => 'Van Zanten','email' => 'nvanzanten@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '202 S 7th Street','city' => 'Grand Haven','postal' => '49417','state' => 'MI','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-04-28','password' => Hash::make('nvanzanten@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Cynthia','last_name' => 'Foster','email' => 'pinkertonb5@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3000 Mistywood Ln.','city' => 'Denton','postal' => '76209','state' => 'Texas','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-04-30','password' => Hash::make('pinkertonb5@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Christina A.','last_name' => 'McFaul','email' => 'christinamcfaul@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1805 walnut ave','city' => 'Wilmette','postal' => '60091','state' => 'IL','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-05-16','password' => Hash::make('christinamcfaul@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Hanna-Liis','last_name' => 'Ostonen','email' => 'hannaliis.ostonen@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Runeberginkatu 31 A 4','city' => 'Helsinki','postal' => '100','state' => 'Helsinki','country' => 'FI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-05-27','password' => Hash::make('hannaliis.ostonen@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vasana','last_name' => 'Chiu','email' => 'vasana1@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '659 Hahaione St','city' => 'Hawaii','postal' => '96825','state' => 'Pacific','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-05-28','password' => Hash::make('vasana1@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Neeraj','last_name' => 'Sirur','email' => 'nsirur@gmail.com','dob' => NULL,'phone' => '9820639467','gender' => NULL,'address' => '35-A Belvedere Court','city' => 'Sane Guruji Marg, Mahalaxmi','postal' => '400011','state' => 'Mumbai','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-05-29','password' => Hash::make('nsirur@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'chnloveNah','last_name' => 'chnloveNah','email' => 'hkunhs@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Russia','city' => 'Russia','postal' => 'CHNLOVE','state' => 'Russia','country' => 'RU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-06-15','password' => Hash::make('hkunhs@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jose Netto','last_name' => 'Estrella','email' => 'jnenx@yahoo.com.br','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'SQN 313, BL G, AP 308','city' => 'Brasilia','postal' => '70766070','state' => 'DF','country' => 'BR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-06-16','password' => Hash::make('jnenx@yahoo.com.br')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sandra','last_name' => 'Oligo','email' => 'sandeeoligo00@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Amana street','city' => 'Kaduna','postal' => '234','state' => 'West Africa','country' => 'NG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-03','password' => Hash::make('sandeeoligo00@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Winnie','last_name' => 'Tam','email' => 'tamwaiying@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Sai Wan Terrace','city' => 'Hong Kong','postal' => '0','state' => 'Hong Kong','country' => 'HK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-04','password' => Hash::make('tamwaiying@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'love-sietsNah','last_name' => 'love-sietsNah','email' => '6y2754hd@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Thai','city' => 'Thai','postal' => 'LOVE-SITES','state' => 'Thai','country' => 'TH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-04','password' => Hash::make('6y2754hd@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Aslam Baig','last_name' => 'Gows Baig','email' => 'rj73650@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Pesit boys hostel, pesit south campus, konappana agrahara','city' => 'Electronic city','postal' => '560100','state' => 'Bangalore','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-09','password' => Hash::make('rj73650@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jon','last_name' => 'Grov','email' => 'jon.grov@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Kantarellveien 83','city' => 'Rasta','postal' => '1476','state' => 'Rasta','country' => 'NO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-16','password' => Hash::make('jon.grov@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Amrij','last_name' => 'Singh','email' => 'amrikmahashya@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '0','city' => 'Kaithal','postal' => '136033','state' => 'Haryana','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-20','password' => Hash::make('amrikmahashya@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sultan Mahmood','last_name' => 'Mahmood','email' => 'sultanmahmood100@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Mirpur','city' => 'Dhaka','postal' => '1216','state' => 'Dhaka','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-20','password' => Hash::make('sultanmahmood100@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Gee','last_name' => 'Joseph','email' => 'stuntgirl@earthlink.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '23147 Park Street','city' => 'USA','postal' => '48124','state' => 'dearborn michigan','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-07-25','password' => Hash::make('stuntgirl@earthlink.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Gloria','last_name' => 'Joseph','email' => 'gloriajoseph@mac.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'x','city' => 'x','postal' => 'X','state' => 'x','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-08','password' => Hash::make('gloriajoseph@mac.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'crystal','last_name' => 'hart','email' => 'combatgigirl@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '22a Devon street','city' => 'wellington','postal' => '6021','state' => 'wellington','country' => 'NZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-11','password' => Hash::make('combatgigirl@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lance','last_name' => 'Levine','email' => 'lance.levine@mfiintl.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '117 Camino Barranca','city' => 'El Paso','postal' => '79912','state' => 'Texas','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-12','password' => Hash::make('lance.levine@mfiintl.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Carl','last_name' => 'Parker','email' => 'cwp342020@comcast.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '9408 Farragut Dr NE','city' => 'Albuquerque ','postal' => '87111','state' => 'NM','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-16','password' => Hash::make('cwp342020@comcast.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jermaine','last_name' => 'Pulley','email' => 'jpulley03@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '925 NW 13th St','city' => 'Blue Springs','postal' => '64015','state' => 'MO','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-16','password' => Hash::make('jpulley03@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit','last_name' => 'mahto','email' => 'test1@test.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Rohini','city' => 'Delhi','postal' => '110085','state' => 'India','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-17','password' => Hash::make('test1@test.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nikhil Siddharth','last_name' => 'Molugu','email' => 'nikhilmolugu72@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3/B, revenue board colony','city' => 'Malakpet','postal' => '500036','state' => 'Hyderabad','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-08-21','password' => Hash::make('nikhilmolugu72@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'fahad','last_name' => 'ali','email' => 'fahadali4r@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '8. shahkot','city' => 'pakistan ','postal' => '38000','state' => 'shahkot ','country' => 'PK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-01','password' => Hash::make('fahadali4r@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'qwereqqe','last_name' => 'sdfdsdsfdfs','email' => 'abc@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'delhi','city' => 'delhi','postal' => '1100202','state' => 'india','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-11','password' => Hash::make('abc@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit','last_name' => 'Mahto','email' => 'subir.kumar110@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Delhi','city' => 'Delhi','postal' => '110085','state' => 'India','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-11','password' => Hash::make('subir.kumar110@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kari og Ole','last_name' => 'Grov','email' => 'kari.grov@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Brochmanns gate 5','city' => 'Oslo','postal' => '460','state' => 'Oslo','country' => 'NO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-16','password' => Hash::make('kari.grov@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bev','last_name' => 'Jay','email' => 'bevjayphotography@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '910 Apukwa Drive','city' => 'Manitou Beach','postal' => 'S0K 4T1','state' => 'Saskatchewan','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-19','password' => Hash::make('bevjayphotography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Gaby','last_name' => 'Janney','email' => 'provence@moulindelaroque.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'le Moulin de la Roque','city' => 'Noves France','postal' => '13550','state' => 'Provence','country' => 'FR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-19','password' => Hash::make('provence@moulindelaroque.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Hoshang','last_name' => 'Hashimi','email' => 'hoshang.herat@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Gulha 1','city' => 'Herat 1','postal' => '3001','state' => 'Afghanistan','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-20','password' => Hash::make('hoshang.herat@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kelly','last_name' => 'Nakamaru','email' => 'princenakamaruphotography@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '874 Windingway Drive','city' => 'Ventura','postal' => '93001','state' => 'CA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-22','password' => Hash::make('princenakamaruphotography@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Manuela','last_name' => 'Molk','email' => 'reality.bites@gmx.at','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Nassweg 13','city' => 'Feldkirchen','postal' => '9560','state' => 'Carinthia','country' => 'AT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-09-30','password' => Hash::make('reality.bites@gmx.at')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nelson','last_name' => 'Shrestha','email' => 'shresthanelson16@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Patan','city' => 'Nepali','postal' => '44700','state' => 'Lalitpur','country' => 'NP','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-10-08','password' => Hash::make('shresthanelson16@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Magen','last_name' => 'Bartless','email' => 'mbartless@icloud.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '211 East Grumling Rd','city' => 'Hodges','postal' => '29653','state' => 'South carolina','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-10-20','password' => Hash::make('mbartless@icloud.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mia','last_name' => 'Singer','email' => 'miasinger@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '37 North Street','city' => 'Leighton Buzzard','postal' => 'LU71EQ','state' => 'Bedfordshire','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-10-23','password' => Hash::make('miasinger@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'mail','last_name' => 'compelling','email' => 'mail@thecompellingimage.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'k','city' => 's','postal' => '41334','state' => 'n','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-11-01','password' => Hash::make('mail@thecompellingimage.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Eddie ','last_name' => 'Goldstein','email' => 'e.goldstein5@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '530 Bowling Green','city' => 'Moorestown','postal' => '8057','state' => 'New Jersey','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-11-02','password' => Hash::make('e.goldstein5@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Steve','last_name' => 'Blackwell','email' => 'steve_blackwell@shaw.ca','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2831 - 13th Avenue NW','city' => 'Calgary','postal' => 'T2N 1M1','state' => 'Alberta','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-11-07','password' => Hash::make('steve_blackwell@shaw.ca')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Elizabeth','last_name' => 'Beall','email' => 'lizbeall@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2222 Irving St. ','city' => 'Denver','postal' => '80211','state' => 'CO','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-11-13','password' => Hash::make('lizbeall@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'McKayla','last_name' => 'Turner','email' => 'mariemckayla196@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '330 W. Monroe Street','city' => 'Kimberly, Idaho','postal' => '83341','state' => 'Shoshone','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-11-25','password' => Hash::make('mariemckayla196@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jim','last_name' => 'Titus','email' => 'jim@jimtitus.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1231 Meadowlawn Pl.','city' => 'Molalla','postal' => '97038','state' => '1231 Meadowlawn Pl.','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-11-30','password' => Hash::make('jim@jimtitus.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mariella','last_name' => 'Candela','email' => 'mariella@mariellaamitai.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '35 C Namshan village','city' => 'Hong Kong','postal' => 'HK01','state' => 'Hong Kong','country' => 'HK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-12-02','password' => Hash::make('mariella@mariellaamitai.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nabin ','last_name' => 'Maharjan','email' => 'antiimbasna@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Samakhusi','city' => 'Kathmandu','postal' => '24001','state' => 'Bagmati','country' => 'NP','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-12-06','password' => Hash::make('antiimbasna@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'David','last_name' => 'Hardy','email' => 'tigertail1@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '117 Aledo Avenue','city' => 'Coral Gables','postal' => '33134','state' => 'Florida','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-12-16','password' => Hash::make('tigertail1@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Remco','last_name' => 'Bogaard','email' => 'remcoo1987@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '14, Kitchener Link #24-27','city' => 'Singapore','postal' => '207223','state' => 'Singapore','country' => 'SG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-12-21','password' => Hash::make('remcoo1987@gmail.com')
        ]);
//        $user->save();
//        $user->assignRole('student');
//        $user = null;
//        $user = User::create([
//            'first_name' => 'The','last_name' => 'Compelling Image','email' => 'testdigi02@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Delhi','city' => 'Rohini','postal' => '110085','state' => 'Asia','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-12-26','password' => Hash::make('testdigi02@gmail.com')
//        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Barry','last_name' => 'Florin','email' => 'bsf2@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Via Vincenzo Consani 80','city' => 'Lucca','postal' => '55100','state' => 'Tuscany','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2017-12-31','password' => Hash::make('bsf2@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Brigit','last_name' => 'Baukal','email' => 'brigittebathgate@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'a','city' => 'b','postal' => '41334','state' => 'c','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-01-02','password' => Hash::make('brigittebathgate@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Noemi','last_name' => 'Adrian','email' => 'leroyadrian@outlook.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6 Val Hervelin','city' => 'Pleudihen','postal' => '22690','state' => 'Bretagne','country' => 'FR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-01-07','password' => Hash::make('leroyadrian@outlook.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jen','last_name' => 'Tucker','email' => 'sweetjen34@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1101 Campbell ','city' => 'Jacksonville FL','postal' => '32207','state' => 'FL ','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-01-28','password' => Hash::make('sweetjen34@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jen','last_name' => 'Tucker','email' => 'sinelniko@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1101 Campbell ','city' => 'Jacksonville FL','postal' => '32207','state' => 'FL ','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-02-10','password' => Hash::make('sinelniko@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Anouchka','last_name' => 'Lototzky','email' => 'anouchka.artmosaique@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Bodenstrasse','city' => 'Balgach','postal' => '9436','state' => 'Schweiz','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-03-06','password' => Hash::make('anouchka.artmosaique@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Boyd','last_name' => 'Turner','email' => 'support@thecompellingimage.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'q','city' => 'd','postal' => 'D','state' => 'f','country' => 'AL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-04-02','password' => Hash::make('support@thecompellingimage.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Natasha','last_name' => 'Miles','email' => 'tashmiles@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '27c Saltoun Road','city' => 'London','postal' => 'SW2 1EN','state' => 'England','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-04-15','password' => Hash::make('tashmiles@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mary','last_name' => 'Kirby','email' => 'kirbymary77@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '82 sundays well, Lisloose','city' => 'Tralee','postal' => 'KERRY','state' => 'Kerry','country' => 'IE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-04-26','password' => Hash::make('kirbymary77@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Christina ','last_name' => 'Sachs','email' => 'wholeheartedchef@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2045 Tapscott ave','city' => 'El Cerrito','postal' => '94530','state' => 'California','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-05-16','password' => Hash::make('wholeheartedchef@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ajita','last_name' => 'Giri','email' => 'ajitagiri26@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'N Jackson Street','city' => 'Starkville','postal' => '39759','state' => 'Mississippi ','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-05-17','password' => Hash::make('ajitagiri26@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rob','last_name' => 'byrd','email' => 'jythonagx@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1315 east blvd #718','city' => 'charlotte','postal' => '28203','state' => 'NC','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-05-28','password' => Hash::make('jythonagx@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lynette','last_name' => 'McKelvie','email' => 'lyn.mckelvie@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4 Betula Avenue','city' => 'Nunawading','postal' => '3131','state' => 'Victoria','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-06-16','password' => Hash::make('lyn.mckelvie@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sumit','last_name' => 'Mahto','email' => 'testing@testing.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'D-17/226 Sector-03','city' => 'NEW DELHI','postal' => '110085','state' => 'Delhi','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-12-19','password' => Hash::make('testing@testing.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kevinnob','last_name' => 'Kevinnob','email' => 'tristysigns@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Axum','city' => 'Axum','postal' => '114315','state' => 'Axum','country' => 'ET','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-12-22','password' => Hash::make('tristysigns@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Новые игровые системы Жми http://go.systsus.com/427488 Не упусти удачу! 3','last_name' => 'Новые игровые системы Жми http://go.systsus.com/427488 Не упусти удачу! 3','email' => 'yulya_vlasova_70@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Biel','city' => 'Biel','postal' => '151332','state' => 'Biel','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-12-22','password' => Hash::make('yulya_vlasova_70@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Это возможно Жми https://jim-s.com/310260 Сделай подарок на новый год! q','last_name' => 'Это возможно Жми https://jim-s.com/310260 Сделай подарок на новый год! q','email' => 'bigaser@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Biel','city' => 'Biel','postal' => '142522','state' => 'Biel','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2018-12-26','password' => Hash::make('bigaser@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Carrie','last_name' => 'Kouri','email' => 'hello@carriekouri.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1850','city' => 'Saint Louis','postal' => '63146','state' => 'Missouri','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-01-09','password' => Hash::make('hello@carriekouri.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'loretaweb7b','last_name' => 'loretaweb7b','email' => 'grybovsergey88@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Хмельницкий','city' => 'Одесса','postal' => '155453','state' => 'Чернигов','country' => 'UA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-01-19','password' => Hash::make('grybovsergey88@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'igarek675l','last_name' => 'igarek675l','email' => 'shamrykenkokatya@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '55 Wildflower Lane','city' => 'Paragon','postal' => '115343','state' => 'North West','country' => 'RU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-01-25','password' => Hash::make('shamrykenkokatya@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'j','last_name' => 'v','email' => 'jvilla83@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'x','city' => 'x','postal' => '12345','state' => 'x','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-19','password' => Hash::make('jvilla83@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Javier','last_name' => 'Villarreal','email' => 'jvilla831@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '415 Newark St','city' => 'Hoboken','postal' => '7030','state' => 'New Jersey','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-19','password' => Hash::make('jvilla831@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Humminbirdjxp','last_name' => 'Humminbirdjxp','email' => 'tinawhitaker441@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '143521','state' => 'Minsk','country' => 'CO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-19','password' => Hash::make('tinawhitaker441@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Blenderpxr','last_name' => 'Blenderpxr','email' => 'tah5682@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '145352','state' => 'Minsk','country' => 'SM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-19','password' => Hash::make('tah5682@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Irrigationper','last_name' => 'Irrigationper','email' => 'john@phalenjohn.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125335','state' => 'Minsk','country' => 'GQ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-19','password' => Hash::make('john@phalenjohn.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Humminbirdpuv','last_name' => 'Humminbirdpuv','email' => 'robm@miroprinting.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '115513','state' => 'Minsk','country' => 'CN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-20','password' => Hash::make('robm@miroprinting.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Amazonnnisg','last_name' => 'Amazonnnisg','email' => 'administrator@shafter.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '154353','state' => 'Minsk','country' => 'PE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-20','password' => Hash::make('administrator@shafter.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sandervko','last_name' => 'Sandervko','email' => 'greenhouse@scacable.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '121513','state' => 'Minsk','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-20','password' => Hash::make('greenhouse@scacable.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Blenderwhk','last_name' => 'Blenderwhk','email' => 'mxmickyv@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '131351','state' => 'Minsk','country' => 'PA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-20','password' => Hash::make('mxmickyv@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Boschutf','last_name' => 'Boschutf','email' => 'deirdrelaba@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '155335','state' => 'Minsk','country' => 'BO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-21','password' => Hash::make('deirdrelaba@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sightsba','last_name' => 'Sightsba','email' => 'bill@vdsina.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '145153','state' => 'Minsk','country' => 'IR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-21','password' => Hash::make('bill@vdsina.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Independentaka','last_name' => 'Independentaka','email' => 'jcarrillo@hallandalebeachfl.gov','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '122123','state' => 'Minsk','country' => 'IO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-21','password' => Hash::make('jcarrillo@hallandalebeachfl.gov')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Securityjwp','last_name' => 'Securityjwp','email' => 'govprg@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '122214','state' => 'Minsk','country' => 'ID','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-23','password' => Hash::make('govprg@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Zodiacerg','last_name' => 'Zodiacerg','email' => 'mayers8586@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '122111','state' => 'Minsk','country' => 'DE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-24','password' => Hash::make('mayers8586@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Feederjwm','last_name' => 'Feederjwm','email' => 'dstrait@sandray.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '114125','state' => 'Minsk','country' => 'MR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-25','password' => Hash::make('dstrait@sandray.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Portablepoh','last_name' => 'Portablepoh','email' => 'admin@cherrychree.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '134521','state' => 'Minsk','country' => 'BW','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-25','password' => Hash::make('admin@cherrychree.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vortexnaf','last_name' => 'Vortexnaf','email' => 'captain.kcdk@globeemail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '131245','state' => 'Minsk','country' => 'UZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-25','password' => Hash::make('captain.kcdk@globeemail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Augustrrb','last_name' => 'Augustrrb','email' => 'jrepp@usairways.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '111443','state' => 'Minsk','country' => 'CH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-25','password' => Hash::make('jrepp@usairways.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Flashpaqxli','last_name' => 'Flashpaqxli','email' => 'dan@sprocketexpress.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '154125','state' => 'Minsk','country' => 'AI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-26','password' => Hash::make('dan@sprocketexpress.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Bluetoothgml','last_name' => 'Bluetoothgml','email' => 'jaerobics3@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '115134','state' => 'Minsk','country' => 'DM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-26','password' => Hash::make('jaerobics3@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Garminzdhk','last_name' => 'Garminzdhk','email' => 'eric@controltechonline.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '154443','state' => 'Minsk','country' => 'IR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-26','password' => Hash::make('eric@controltechonline.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Artisanxio','last_name' => 'Artisanxio','email' => 'mlancaster@rollercoat.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '132434','state' => 'Minsk','country' => 'SK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-04-28','password' => Hash::make('mlancaster@rollercoat.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Rigidiqb','last_name' => 'Rigidiqb','email' => 'liacercoca@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '143233','state' => 'Minsk','country' => 'RW','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-01','password' => Hash::make('liacercoca@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Foamejz','last_name' => 'Foamejz','email' => 'support@vdsina.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '151122','state' => 'Minsk','country' => 'AM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-03','password' => Hash::make('support@vdsina.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Edelbrocktyy','last_name' => 'Edelbrocktyy','email' => 'jason@harrismicro.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '152455','state' => 'Minsk','country' => 'MR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-04','password' => Hash::make('jason@harrismicro.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Wilsonraxia','last_name' => 'Wilsonraxia','email' => 'solonyak.pyotr@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Bamako','city' => 'Bamako','postal' => '114213','state' => 'Bamako','country' => 'ML','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-06','password' => Hash::make('solonyak.pyotr@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Ascentvsm','last_name' => 'Ascentvsm','email' => 'director@vdsina.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '121141','state' => 'Minsk','country' => 'MX','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-08','password' => Hash::make('director@vdsina.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Rachioodm','last_name' => 'Rachioodm','email' => 'mfindura@beveragedist.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '124355','state' => 'Minsk','country' => 'DM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-09','password' => Hash::make('mfindura@beveragedist.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Batteriesvks','last_name' => 'Batteriesvks','email' => 'orattanaphasouk@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '142354','state' => 'Minsk','country' => 'AR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-10','password' => Hash::make('orattanaphasouk@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Cutterqoq','last_name' => 'Cutterqoq','email' => 'rhonda@compressorelements.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125234','state' => 'Minsk','country' => 'JM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-11','password' => Hash::make('rhonda@compressorelements.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Fingerboardwvd','last_name' => 'Fingerboardwvd','email' => 'mcarbonaro6@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '122445','state' => 'Minsk','country' => 'SK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-12','password' => Hash::make('mcarbonaro6@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Artisanciy','last_name' => 'Artisanciy','email' => 'kenny@ridetireblocks.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '112452','state' => 'Minsk','country' => 'HU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-12','password' => Hash::make('kenny@ridetireblocks.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Arnottbgo','last_name' => 'Arnottbgo','email' => 'patricia.coates@durhamnc.gov','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '143554','state' => 'Minsk','country' => 'TM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-12','password' => Hash::make('patricia.coates@durhamnc.gov')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Documentjbq','last_name' => 'Documentjbq','email' => 'pingi1984@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '132151','state' => 'Minsk','country' => 'IE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-13','password' => Hash::make('pingi1984@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Airbladewva','last_name' => 'Airbladewva','email' => 'pingapong@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '131443','state' => 'Minsk','country' => 'DM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-13','password' => Hash::make('pingapong@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sighteqa','last_name' => 'Sighteqa','email' => 'director@mchost.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125141','state' => 'Minsk','country' => 'SN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-14','password' => Hash::make('director@mchost.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Flexibleizz','last_name' => 'Flexibleizz','email' => 'caitlinchall@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '143221','state' => 'Minsk','country' => 'TR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-14','password' => Hash::make('caitlinchall@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Candyqqu','last_name' => 'Candyqqu','email' => 'jessicajhoneycutt@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '113232','state' => 'Minsk','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-14','password' => Hash::make('jessicajhoneycutt@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'RainMachinehsr','last_name' => 'RainMachinehsr','email' => 'dlwilliams@stgeorgefire.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '124123','state' => 'Minsk','country' => 'ET','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-15','password' => Hash::make('dlwilliams@stgeorgefire.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Rigidcmm','last_name' => 'Rigidcmm','email' => 'rgmusa@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '143515','state' => 'Minsk','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-16','password' => Hash::make('rgmusa@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'CHIRPwza','last_name' => 'CHIRPwza','email' => 'office@gateway-team.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '114541','state' => 'Minsk','country' => 'SA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-17','password' => Hash::make('office@gateway-team.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Generationxuq','last_name' => 'Generationxuq','email' => 'mholman2789@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '135355','state' => 'Minsk','country' => 'LS','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-21','password' => Hash::make('mholman2789@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Infraredzlh','last_name' => 'Infraredzlh','email' => 'iveilleux23@videotron.ca','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '145542','state' => 'Minsk','country' => 'AL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-24','password' => Hash::make('iveilleux23@videotron.ca')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Independenttzp','last_name' => 'Independenttzp','email' => 'steinbachtracey@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '135244','state' => 'Minsk','country' => 'NZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-24','password' => Hash::make('steinbachtracey@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'CHIRPtsq','last_name' => 'CHIRPtsq','email' => 'canpimatdi@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '123225','state' => 'Minsk','country' => 'HU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-25','password' => Hash::make('canpimatdi@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Documentgkc','last_name' => 'Documentgkc','email' => 'cferreira@amfmanagement.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '131453','state' => 'Minsk','country' => 'AD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-25','password' => Hash::make('cferreira@amfmanagement.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Stanmoreoww','last_name' => 'Stanmoreoww','email' => 'jimpeters@egner-peters.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '124323','state' => 'Minsk','country' => 'TD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-29','password' => Hash::make('jimpeters@egner-peters.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vitamixbvr','last_name' => 'Vitamixbvr','email' => 'estelle@sanchelimaint.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '155525','state' => 'Minsk','country' => 'AZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-30','password' => Hash::make('estelle@sanchelimaint.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sightgbw','last_name' => 'Sightgbw','email' => 'danielle4656@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '111441','state' => 'Minsk','country' => 'LS','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-05-30','password' => Hash::make('danielle4656@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'BlackVuezbm','last_name' => 'BlackVuezbm','email' => 'crazychu888@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '121511','state' => 'Minsk','country' => 'KP','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-01','password' => Hash::make('crazychu888@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vitamixkie','last_name' => 'Vitamixkie','email' => 'dunn3@midco.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '131453','state' => 'Minsk','country' => 'HN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-01','password' => Hash::make('dunn3@midco.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Fingerboardfev','last_name' => 'Fingerboardfev','email' => 'blmoose@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '144435','state' => 'Minsk','country' => 'GT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-02','password' => Hash::make('blmoose@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dysonbsr','last_name' => 'Dysonbsr','email' => 'kara@drblaw.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125425','state' => 'Minsk','country' => 'LB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-03','password' => Hash::make('kara@drblaw.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Interfacefjb','last_name' => 'Interfacefjb','email' => 'judi@wvfree.org','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '155312','state' => 'Minsk','country' => 'UZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-03','password' => Hash::make('judi@wvfree.org')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Premiumeal','last_name' => 'Premiumeal','email' => 'progkarehurl@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '112345','state' => 'Minsk','country' => 'SK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-07','password' => Hash::make('progkarehurl@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Carpetbof','last_name' => 'Carpetbof','email' => 'porsbubilmoo@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '123444','state' => 'Minsk','country' => 'DZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-06-11','password' => Hash::make('porsbubilmoo@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'CHIRPfnm','last_name' => 'CHIRPfnm','email' => 'liprabeajohn@mail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '121333','state' => 'Minsk','country' => 'BI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-07-03','password' => Hash::make('liprabeajohn@mail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Edelbrockpdl','last_name' => 'Edelbrockpdl','email' => 'lotserefcheck@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '111534','state' => 'Minsk','country' => 'LB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-07-04','password' => Hash::make('lotserefcheck@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Flukerzg','last_name' => 'Flukerzg','email' => 'violighfaten@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '135515','state' => 'Minsk','country' => 'EE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-07-06','password' => Hash::make('violighfaten@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'pradeep ','last_name' => 'Beniwal','email' => 'pradeep.kumar+user@decorist.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'test','city' => 'india','postal' => '125052','state' => 'Asia','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-07-11','password' => Hash::make('pradeep.kumar+user@decorist.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Drywallrvu','last_name' => 'Drywallrvu','email' => 'gpeterson127@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '145454','state' => 'Minsk','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-07-12','password' => Hash::make('gpeterson127@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Wilsonraxia','last_name' => 'Wilsonraxia','email' => 'trishi69tkohel@gmx.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Bamako','city' => 'Bamako','postal' => '114221','state' => 'Bamako','country' => 'ML','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-07-25','password' => Hash::make('trishi69tkohel@gmx.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Speakergtg','last_name' => 'Speakergtg','email' => 'pattileigh@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125214','state' => 'Minsk','country' => 'SE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-06','password' => Hash::make('pattileigh@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Minelabnvr','last_name' => 'Minelabnvr','email' => 'asbest@x-store.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '113444','state' => 'Minsk','country' => 'LT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-09','password' => Hash::make('asbest@x-store.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Charlie','last_name' => 'Jones','email' => 'cjones6@sbcglobal.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '5049 Alejo Street','city' => 'San Diego','postal' => '92124','state' => 'CA','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-10','password' => Hash::make('cjones6@sbcglobal.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sunburstmpu','last_name' => 'Sunburstmpu','email' => 'ctodpacargold@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125134','state' => 'Minsk','country' => 'BO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-15','password' => Hash::make('ctodpacargold@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'pradeep','last_name' => 'Beniwal','email' => 'pradeep.kumar@decorist.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'test','city' => 'in','postal' => '125052','state' => 'Asia','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-19','password' => Hash::make('pradeep.kumar@decorist.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sanderksj','last_name' => 'Sanderksj','email' => 'marian@bdrafting.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125444','state' => 'Minsk','country' => 'PA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-20','password' => Hash::make('marian@bdrafting.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sanderbwq','last_name' => 'Sanderbwq','email' => 'jputnam@arcastech.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '151353','state' => 'Minsk','country' => 'KH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-20','password' => Hash::make('jputnam@arcastech.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Candygif','last_name' => 'Candygif','email' => 'megalecpa@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '142522','state' => 'Minsk','country' => 'PL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-21','password' => Hash::make('megalecpa@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Feederemn','last_name' => 'Feederemn','email' => 'mtcarl@cox.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '114441','state' => 'Minsk','country' => 'LB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-21','password' => Hash::make('mtcarl@cox.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Minelabvdc','last_name' => 'Minelabvdc','email' => 'florlorenzo@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '131443','state' => 'Minsk','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-23','password' => Hash::make('florlorenzo@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Nespressoxxy','last_name' => 'Nespressoxxy','email' => 'idubceac@bpmplus.md','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '153525','state' => 'Minsk','country' => 'IL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-23','password' => Hash::make('idubceac@bpmplus.md')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Linksysloy','last_name' => 'Linksysloy','email' => 'fburk@sbcglobal.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '111545','state' => 'Minsk','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-24','password' => Hash::make('fburk@sbcglobal.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sandergge','last_name' => 'Sandergge','email' => 'jakubweigl@seznam.cz','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '144354','state' => 'Minsk','country' => 'AO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-27','password' => Hash::make('jakubweigl@seznam.cz')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Pouringmmj','last_name' => 'Pouringmmj','email' => 'mstark88@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '154124','state' => 'Minsk','country' => 'BE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-28','password' => Hash::make('mstark88@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Documentgze','last_name' => 'Documentgze','email' => 'akhenaten23@comcast.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '134154','state' => 'Minsk','country' => 'GU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-08-29','password' => Hash::make('akhenaten23@comcast.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vitamixzhg','last_name' => 'Vitamixzhg','email' => 'prophorsila@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '144251','state' => 'Minsk','country' => 'BE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-02','password' => Hash::make('prophorsila@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Annotationsriy','last_name' => 'Annotationsriy','email' => 'morganagan810@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '142422','state' => 'Minsk','country' => 'GM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-02','password' => Hash::make('morganagan810@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Fortressmcc','last_name' => 'Fortressmcc','email' => 'msullivan@kensingtoncaterers.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '142224','state' => 'Minsk','country' => 'PE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-03','password' => Hash::make('msullivan@kensingtoncaterers.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Squierxsm','last_name' => 'Squierxsm','email' => 'kbrown@tkc.edu','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '124235','state' => 'Minsk','country' => 'FI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-04','password' => Hash::make('kbrown@tkc.edu')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Furrionvcr','last_name' => 'Furrionvcr','email' => 'garyh@airmail.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '142122','state' => 'Minsk','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-07','password' => Hash::make('garyh@airmail.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Yamahahry','last_name' => 'Yamahahry','email' => 'ahmedeldannaoui@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125111','state' => 'Minsk','country' => 'BR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-08','password' => Hash::make('ahmedeldannaoui@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mojavepni','last_name' => 'Mojavepni','email' => 'biblawaper@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '135331','state' => 'Minsk','country' => 'MG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-09','password' => Hash::make('biblawaper@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Leupoldohi','last_name' => 'Leupoldohi','email' => 'apoc1589@aim.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '113315','state' => 'Minsk','country' => 'KG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-10','password' => Hash::make('apoc1589@aim.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Fenderydw','last_name' => 'Fenderydw','email' => 'rba1090@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '132435','state' => 'Minsk','country' => 'FR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-09-10','password' => Hash::make('rba1090@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ari','last_name' => 'gou','email' => 'arielle.goubert@yahoo.fr','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'callejon lemus','city' => 'antigua','postal' => '100','state' => 'sacatepequez','country' => 'GT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-10-09','password' => Hash::make('arielle.goubert@yahoo.fr')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Susan','last_name' => 'Urbania','email' => 'smwu31@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '40 Merry Lane','city' => 'Weston','postal' => '12345','state' => 'Connectyicuty','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'Eastern Time (US & Canada)','created_at' => '2019-12-22','password' => Hash::make('smwu31@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dhaya','last_name' => 'Karuna','email' => 'dhaya4rails@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'baner','city' => 'Baner','postal' => '411045','state' => 'pune','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2019-12-23','password' => Hash::make('dhaya4rails@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'peruash','last_name' => 'peruash','email' => 'gbragin91@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '133332','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-01-01','password' => Hash::make('gbragin91@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sashaa','last_name' => 'sashaa','email' => 'fshi85@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '155322','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-01-21','password' => Hash::make('fshi85@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sashamol','last_name' => 'sashamol','email' => 'pasha.malin.01@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '134534','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-01-24','password' => Hash::make('pasha.malin.01@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sashaa','last_name' => 'sashaa','email' => 'polya.markina.94@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '152221','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-01-29','password' => Hash::make('polya.markina.94@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'fgohik','last_name' => 'fgohik','email' => 'polya.marfina@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '144114','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-02-23','password' => Hash::make('polya.marfina@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kimberly','last_name' => 'Reine','email' => 'kimberly_reine@yahoo.ca','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7424 Louis-Hebert','city' => 'Montreal','postal' => 'H2E 2X6','state' => 'QC','country' => 'CA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-02-28','password' => Hash::make('kimberly_reine@yahoo.ca')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'maniiya','last_name' => 'maniiya','email' => 'pasha.proshkin.04@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '144135','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-02-29','password' => Hash::make('pasha.proshkin.04@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'NIFEJEFГРРРШШРГПIHIJIJUHшШОШРШООШО ОШОШГШОЩРГIYIIYIIJIYUYшШНГЕНППРП МВАЫОАСЛЫВТМРВШАСОЦЫЩМТАУЛВРАШ https://jfjehuchsajcfbgsjfh.fudgcushcsgucgsudfchudshfcsihcsug.com/uugsdcuwjshjdsghfgsujfcdjgvbudhud','last_name' => 'NIFEJEFГРРРШШРГПIHIJIJUHшШОШРШООШО ОШОШГШОЩРГIYIIYIIJIYUYшШНГЕНППРП МВАЫОАСЛЫВТМРВШАСОЦЫЩМТАУЛВРАШ https://jfjehuchsajcfbgsjfh.fudgcushcsgucgsudfchudshfcsihcsug.com/uugsdcuwjshjdsghfgsujfcdjgvbudhud','email' => 'michaelsog@muabanmaychieu.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'NIFEJEFГРРРШШРГПIHIJIJUHшШОШРШООШО ОШОШГШОЩРГIYIIYIIJIYUYшШНГЕНППРП МВАЫОАСЛЫВТМРВШАСОЦЫЩМТАУЛВРАШ https://jfjehuchsajcfbgsjfh.fudgcushcsgucgsudfchudshfcsihcsug.com/uugsdcuwjshjdsghfgsujfcdjgvbudhud','city' => 'NIFEJEFГРРРШШРГПIHIJIJUHшШОШРШООШО ОШОШГШОЩРГIYIIYIIJIYUYшШНГЕНППРП МВАЫОАСЛЫВТМРВШАСОЦЫЩМТАУЛВРАШ https://jfjehuchsajcfbgsjfh.fudgcushcsgucgsudfchudshfcsihcsug.com/uugsdcuwjshjdsghfgsujfcdjgvbudhud','postal' => '152412','state' => 'NIFEJEFГРРРШШРГПIHIJIJUHшШОШРШООШО ОШОШГШОЩРГIYIIYIIJIYUYшШНГЕНППРП МВАЫОАСЛЫВТМРВШАСОЦЫЩМТАУЛВРАШ https://jfjehuchsajcfbgsjfh.fudgcushcsgucgsudfchudshfcsihcsug.com/uugsdcuwjshjdsghfgsujfcdjgvbudhud','country' => 'MG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-03-08','password' => Hash::make('michaelsog@muabanmaychieu.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'gruppi','last_name' => 'gruppi','email' => 'gmaroshkina@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '142324','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-03-17','password' => Hash::make('gmaroshkina@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'RachlJab','last_name' => 'RachlJab','email' => 'wifgow@josephay905s.changeip.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Chikago','city' => 'Chikago','postal' => '155123','state' => 'Chikago','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-03-25','password' => Hash::make('wifgow@josephay905s.changeip.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'gruppi','last_name' => 'gruppi','email' => 'sashgorl@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '112135','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-04-23','password' => Hash::make('sashgorl@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'grihsau','last_name' => 'grihsau','email' => 'ghpashuu@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '114451','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-01','password' => Hash::make('ghpashuu@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Alex','last_name' => 'Zimmer','email' => 'alex.zimmer09@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4537 TR 232','city' => 'Marengo','postal' => '43334','state' => 'OH','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-06','password' => Hash::make('alex.zimmer09@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'mgmahaw','last_name' => 'mgmahaw','email' => 'mahyi11@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '124425','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-12','password' => Hash::make('mahyi11@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Artisanvlf','last_name' => 'Artisanvlf','email' => 'joshnovosad91@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '132535','state' => 'Minsk','country' => 'NE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-26','password' => Hash::make('joshnovosad91@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Speakerroq','last_name' => 'Speakerroq','email' => 'zack1202@me.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '124534','state' => 'Minsk','country' => 'ZA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-26','password' => Hash::make('zack1202@me.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'TariaChaing','last_name' => 'TariaChaing','email' => 'iuliiasimonova589949@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ligatne','city' => 'Ligatne','postal' => '133115','state' => 'Ligatne','country' => 'LV','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-28','password' => Hash::make('iuliiasimonova589949@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'petyanweign','last_name' => 'petyanweign','email' => 'wovesejg@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7501 N. Jog Road','city' => 'Renegade','postal' => '135553','state' => 'New York','country' => 'NU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-05-30','password' => Hash::make('wovesejg@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'tixanovEmask','last_name' => 'tixanovEmask','email' => 'tixanovskicom@meta.ua','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4323 laurel pl','city' => 'Paragon','postal' => '143442','state' => 'Central Florida','country' => 'RE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-01','password' => Hash::make('tixanovskicom@meta.ua')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Minelabmok','last_name' => 'Minelabmok','email' => 'zacha@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '125243','state' => 'Minsk','country' => 'PL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-03','password' => Hash::make('zacha@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'dflzweign','last_name' => 'dflzweign','email' => 'tsio.mar2@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'P.O. Box CC','city' => 'Paragon','postal' => '124541','state' => 'Central California','country' => 'GR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-06','password' => Hash::make('tsio.mar2@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'tdljczweign','last_name' => 'tdljczweign','email' => 'elenariabova0132@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '500A NORTH ELLIS RD.','city' => 'Paragon','postal' => '155154','state' => 'Washington','country' => 'GT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-13','password' => Hash::make('elenariabova0132@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sunburstmqf','last_name' => 'Sunburstmqf','email' => 'jerry@maze3.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '141432','state' => 'Minsk','country' => 'IL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-14','password' => Hash::make('jerry@maze3.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'luis','last_name' => 'armijo','email' => 'lsarmijo0@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Av San Borja Norte 865, Departamento 402','city' => 'Lima','postal' => '15021','state' => 'Lima','country' => 'PE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-19','password' => Hash::make('lsarmijo0@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'maksyutaweign','last_name' => 'maksyutaweign','email' => 'manchalena7@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4024 Dr. Love Road','city' => 'Neutral','postal' => '141433','state' => 'South Texas','country' => 'BD','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-20','password' => Hash::make('manchalena7@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'dbneczweign','last_name' => 'dbneczweign','email' => 'lpolatkina1@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '890 Iroquois Ave.','city' => 'Paragon','postal' => '142433','state' => 'Gauteng','country' => 'KZ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-06-27','password' => Hash::make('lpolatkina1@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Fesiusweign','last_name' => 'Fesiusweign','email' => 'palguevadiana6@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '186 S Halifax Dr','city' => 'Neutral','postal' => '145444','state' => 'West','country' => 'BN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-07-09','password' => Hash::make('palguevadiana6@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Merillthund','last_name' => 'Merillthund','email' => 'he.n.ta.iw.orl.d.p.ict.ur.es5@gdemoy.site','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '151152','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-07-17','password' => Hash::make('he.n.ta.iw.orl.d.p.ict.ur.es5@gdemoy.site')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'cntgfyblfweign','last_name' => 'cntgfyblfweign','email' => 'petryuliya4@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '16 Corning Avenue','city' => 'Neutral','postal' => '112124','state' => 'North West','country' => 'IR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-07-17','password' => Hash::make('petryuliya4@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'RataMot','last_name' => 'RataMot','email' => 'bvlad19rota@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Львов','city' => 'Киев','postal' => '111235','state' => 'Киев','country' => 'BL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-07-18','password' => Hash::make('bvlad19rota@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Arthurshire','last_name' => 'Arthurshire','email' => '123teatr@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Piran','city' => 'Piran','postal' => '133443','state' => 'Piran','country' => 'SI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-07-23','password' => Hash::make('123teatr@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'dbntifweign','last_name' => 'dbntifweign','email' => 'logevgeniya3@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'None','city' => 'Paragon','postal' => '112445','state' => 'Mpumalanga','country' => 'BV','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-07-30','password' => Hash::make('logevgeniya3@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Infraredahd','last_name' => 'Infraredahd','email' => 'smith@egyptian.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '145353','state' => 'Minsk','country' => 'PH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-02','password' => Hash::make('smith@egyptian.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'fhntvmirfweign','last_name' => 'fhntvmirfweign','email' => 'zhenyaegorina1@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'None','city' => 'Paragon','postal' => '132152','state' => 'North Florida','country' => 'HN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-05','password' => Hash::make('zhenyaegorina1@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'hoveringweign','last_name' => 'hoveringweign','email' => 'lyudmbazhanova1@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1808 PaineAve.','city' => 'Neutral','postal' => '133252','state' => 'South Texas','country' => 'TM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-11','password' => Hash::make('lyudmbazhanova1@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'AlekZenderr_Smir','last_name' => 'AlekZenderr_Smir','email' => 'karli2020f@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Moscow','city' => 'Moscow','postal' => '111454','state' => 'Moscow','country' => 'RU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-21','password' => Hash::make('karli2020f@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kirka_nex','last_name' => 'Kirka_nex','email' => 'kari332211@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Москва','city' => 'Москва','postal' => '124155','state' => 'Москва','country' => 'AL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-25','password' => Hash::make('kari332211@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'valenvv','last_name' => 'valenvv','email' => 'ibanenkiivan96@rambler.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Ровно','city' => 'Ивано-Франковск','postal' => '123323','state' => 'Днепр','country' => 'BL','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-27','password' => Hash::make('ibanenkiivan96@rambler.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sahaha','last_name' => 'sahaha','email' => 'gorlina1958@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '112324','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-29','password' => Hash::make('gorlina1958@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'profpErofe','last_name' => 'profpErofe','email' => 'profptb@meta.ua','dob' => NULL,'phone' => '','gender' => NULL,'address' => '16 Corning Avenue','city' => 'Paragon','postal' => '131552','state' => 'South Carolina','country' => 'JP','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-08-30','password' => Hash::make('profptb@meta.ua')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Thomas','last_name' => 'Mangione','email' => 'mangione326@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2801 Hopkins Road','city' => 'Amherst, NY','postal' => '14228','state' => 'New York','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-01','password' => Hash::make('mangione326@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'MicziwalCrolf','last_name' => 'MicziwalCrolf','email' => 'micziwal@go2.pl','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Kwajalein','city' => 'Kwajalein','postal' => '125444','state' => 'Kwajalein','country' => 'MH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-02','password' => Hash::make('micziwal@go2.pl')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'petrushweign','last_name' => 'petrushweign','email' => 'olegodinokov25@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'MARYLAND','city' => 'Renegade','postal' => '135515','state' => 'South Texas','country' => 'BN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-06','password' => Hash::make('olegodinokov25@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'sahaha','last_name' => 'sahaha','email' => 'hdgor@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '111234','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-06','password' => Hash::make('hdgor@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'panyuhaweign','last_name' => 'panyuhaweign','email' => 'zhanm0313@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3945 Octave Drive','city' => 'Neutral','postal' => '143535','state' => 'Central','country' => 'SB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-11','password' => Hash::make('zhanm0313@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Zacesebaqoo','last_name' => 'Zacesebaqoo','email' => 'pjxw@iroquzap.asia','dob' => NULL,'phone' => '','gender' => NULL,'address' => '210 Prospect Avenue','city' => 'Paragon','postal' => '154512','state' => 'North West','country' => 'KG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-12','password' => Hash::make('pjxw@iroquzap.asia')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'andIweign','last_name' => 'andIweign','email' => 'bektemirovbekzat3@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4310 Falling Leaf Ct','city' => 'Paragon','postal' => '131135','state' => 'North Florida','country' => 'BJ','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-16','password' => Hash::make('bektemirovbekzat3@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Valeryasaweign','last_name' => 'Valeryasaweign','email' => 'tamaraandreeva709@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Aasasasaa','city' => 'Neutral','postal' => '153242','state' => 'Western Cape','country' => 'ZA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-20','password' => Hash::make('tamaraandreeva709@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rbhyzweign','last_name' => 'rbhyzweign','email' => 'og.mixa228@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Captain Misha 19','city' => 'Renegade','postal' => '155514','state' => 'Southern New England','country' => 'BI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-23','password' => Hash::make('og.mixa228@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'dahar','last_name' => 'dahar','email' => 'gorl1985@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '141531','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-23','password' => Hash::make('gorl1985@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lidiya','last_name' => 'Ivannikova','email' => 'lidiya.ivanna1985@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Astoria ','city' => 'Astoria','postal' => '11105','state' => 'USA','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-25','password' => Hash::make('lidiya.ivanna1985@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Antonio','last_name' => 'Marinez','email' => 'antoniomarinez@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'P. O. Box 25014','city' => 'Tampa','postal' => '33622','state' => 'FL','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-26','password' => Hash::make('antoniomarinez@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'MerillFlams','last_name' => 'MerillFlams','email' => 'hent.a.i.w.o.rl.dpi.ctur.es5@gdemoy.site','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '133243','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-09-29','password' => Hash::make('hent.a.i.w.o.rl.dpi.ctur.es5@gdemoy.site')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'olyabibykova','last_name' => 'olyabibykova','email' => 'olyabibykova@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Axum','city' => 'Axum','postal' => '144144','state' => 'Axum','country' => 'ET','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-12','password' => Hash::make('olyabibykova@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Lauraquic','last_name' => 'Lauraquic','email' => 'gildas@sakhpubo.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4701 NW 35 Avenue','city' => 'Paragon','postal' => '152553','state' => 'Limpopo','country' => 'KR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-15','password' => Hash::make('gildas@sakhpubo.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rimmfus','last_name' => 'rimmfus','email' => 's_gorlin@list.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '133354','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-15','password' => Hash::make('s_gorlin@list.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Giuseppe','last_name' => 'Potente','email' => 'gepoti1@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Via Giovanni Severano 25','city' => 'Rome','postal' => '161','state' => 'Rome','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-15','password' => Hash::make('gepoti1@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'lidasrudova','last_name' => 'lidasrudova','email' => 'lidasrudova@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Karak','city' => 'Karak','postal' => '124252','state' => 'Karak','country' => 'JO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-20','password' => Hash::make('lidasrudova@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Matt','last_name' => 'Badenoch','email' => 'matt@mattbadenoch.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Church Road','city' => 'Oxfordshire','postal' => 'OX10 6SF','state' => 'United Kingdom','country' => 'GB','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-21','password' => Hash::make('matt@mattbadenoch.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'krutovzhorik','last_name' => 'krutovzhorik','email' => 'krutovzhorik@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Aarschot','city' => 'Aarschot','postal' => '124443','state' => 'Aarschot','country' => 'BE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-24','password' => Hash::make('krutovzhorik@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'riemtrundova','last_name' => 'riemtrundova','email' => 'riemtrundova@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Monrovia','city' => 'Monrovia','postal' => '155253','state' => 'Monrovia','country' => 'LR','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-24','password' => Hash::make('riemtrundova@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'indiramihina','last_name' => 'indiramihina','email' => 'indiramihina@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Lusaka','city' => 'Lusaka','postal' => '155442','state' => 'Lusaka','country' => 'ZM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-24','password' => Hash::make('indiramihina@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sankalp','last_name' => 'Roy','email' => 'sankalpsroy@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '9 Pannase Colony Ashtavinayak Nagar Jaitala Road','city' => 'Maharashtra','postal' => '440036','state' => 'Maharashtra','country' => 'IN','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-26','password' => Hash::make('sankalpsroy@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Artisanaps','last_name' => 'Artisanaps','email' => 'monkeygirlinri@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '142542','state' => 'Minsk','country' => 'NP','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-28','password' => Hash::make('monkeygirlinri@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Pouringxjq','last_name' => 'Pouringxjq','email' => 'marsha.carson@att.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '134332','state' => 'Minsk','country' => 'JO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-10-29','password' => Hash::make('marsha.carson@att.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'rimmfuss','last_name' => 'rimmfuss','email' => '66lisa81@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '121531','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-07','password' => Hash::make('66lisa81@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'AWE64','last_name' => 'AWE64','email' => '2antonovalesha1988+umax@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Bottegone','city' => 'Bottegone','postal' => '133543','state' => 'Bottegone','country' => 'IT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-08','password' => Hash::make('2antonovalesha1988+umax@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'j.ent.a.iw.orl.d.pic.t.uret5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '112425','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-09','password' => Hash::make('j.ent.a.iw.orl.d.pic.t.uret5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Businessjzy','last_name' => 'Businessjzy','email' => 'nicolep@ogdenre.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '122525','state' => 'Minsk','country' => 'NI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-11','password' => Hash::make('nicolep@ogdenre.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Minelabgpo','last_name' => 'Minelabgpo','email' => 'northeast@satx.rr.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '155335','state' => 'Minsk','country' => 'SK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-14','password' => Hash::make('northeast@satx.rr.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Marigelvquic','last_name' => 'Marigelvquic','email' => 'marigelv@sakhpubo.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => '1236 Locksley Lane','city' => 'Neutral','postal' => '111531','state' => 'Northern New England','country' => 'ZA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-15','password' => Hash::make('marigelv@sakhpubo.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vitalii','last_name' => 'Muravev','email' => 'muravyev.vit@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'B. Sampsonievskiy','city' => 'Saint Petersburg','postal' => '194044','state' => 'Russia','country' => 'RU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-18','password' => Hash::make('muravyev.vit@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Hugitiusquic','last_name' => 'Hugitiusquic','email' => 'hugitius@sakhpubo.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => '2916 Golden Birch Ln','city' => 'Neutral','postal' => '142515','state' => 'North Florida','country' => 'AI','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-19','password' => Hash::make('hugitius@sakhpubo.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillSog','last_name' => 'InvillSog','email' => 'j.ent.a.i.wo.r.l.dp.i.c.t.u.r.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '125334','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-19','password' => Hash::make('j.ent.a.i.wo.r.l.dp.i.c.t.u.r.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'unpNkuqvhgqsAxe https://www.google.com/','last_name' => 'unpNkuqvhgqsAxe https://www.google.com/','email' => 'valeriivorobushkin@yandex.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'РњРѕСЃРєРІР°','city' => 'РњРѕСЃРєРІР°','postal' => 'РЊРЅСЃРЄРІР°','state' => 'РњРѕСЃРєРІР°','country' => 'PF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-19','password' => Hash::make('valeriivorobushkin@yandex.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'bngvwcqvlj','last_name' => 'bngvwcqvlj','email' => 'tuopu865chenhuang@163.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Porsgrunn','city' => 'Porsgrunn','postal' => '133411','state' => 'Porsgrunn','country' => 'NO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-27','password' => Hash::make('tuopu865chenhuang@163.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'modwynquic','last_name' => 'modwynquic','email' => 'modwyn@sakhpubo.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => '210 Prospect Avenue','city' => 'Renegade','postal' => '142433','state' => 'North Florida','country' => 'MC','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-27','password' => Hash::make('modwyn@sakhpubo.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Interfacerjp','last_name' => 'Interfacerjp','email' => 'abel198915@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '114134','state' => 'Minsk','country' => 'MG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-27','password' => Hash::make('abel198915@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Muhammad Corkery','last_name' => 'Muhammad Corkery','email' => 'kkd7777@aol.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '4598 Assunta Forks','city' => '4598 Assunta Forks','postal' => '4598 ASSUNTA FORKS','state' => '4598 Assunta Forks','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-30','password' => Hash::make('kkd7777@aol.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Boris https://ya.ru','last_name' => 'Boris https://ya.ru','email' => 'vucqadpcaphsapp@list.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'РњРѕСЃРєРІР°','city' => 'РњРѕСЃРєРІР°','postal' => 'РЊРЅСЃРЄРІР°','state' => 'РњРѕСЃРєРІР°','country' => 'VE','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-11-30','password' => Hash::make('vucqadpcaphsapp@list.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Asia Gorczany','last_name' => 'Asia Gorczany','email' => 'robertray2009@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '261 Padberg Greens','city' => '261 Padberg Greens','postal' => '261 PADBERG GREENS','state' => '261 Padberg Greens','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-01','password' => Hash::make('robertray2009@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillSog','last_name' => 'InvillSog','email' => 'jenta.i.wo.rld.pictu.r.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '125424','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-01','password' => Hash::make('jenta.i.wo.rld.pictu.r.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Noemie Tremblay','last_name' => 'Noemie Tremblay','email' => 'nicholas.young@vvscanteen.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '124 Altenwerth Lane','city' => '124 Altenwerth Lane','postal' => '124 ALTENWERTH LANE','state' => '124 Altenwerth Lane','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-03','password' => Hash::make('nicholas.young@vvscanteen.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'oghmaniusquic','last_name' => 'oghmaniusquic','email' => 'oghmanius@sakhpubo.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3640 ALAN DR','city' => 'Paragon','postal' => '131343','state' => 'Western Cape','country' => 'AG','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-03','password' => Hash::make('oghmanius@sakhpubo.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'jen.taiwo.rl.dpic.t.u.r.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '112544','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-07','password' => Hash::make('jen.taiwo.rl.dpic.t.u.r.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'AkexeyDog','last_name' => 'AkexeyDog','email' => 'byrdikalex@rambler.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Harkiv','city' => 'Harkiv','postal' => '114341','state' => 'Harkiv','country' => 'UA','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-09','password' => Hash::make('byrdikalex@rambler.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Linksyskho','last_name' => 'Linksyskho','email' => 'maria_gallardo@ncsu.edu','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Minsk','city' => 'Minsk','postal' => '123411','state' => 'Minsk','country' => 'GT','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-09','password' => Hash::make('maria_gallardo@ncsu.edu')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'j.ent.a.i.w.o.rldpict.ur.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '132552','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-10','password' => Hash::make('j.ent.a.i.w.o.rldpict.ur.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'jen.t.a.iwo.r.l.d.pi.c.t.ur.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '155131','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-10','password' => Hash::make('jen.t.a.iwo.r.l.d.pi.c.t.ur.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillSog','last_name' => 'InvillSog','email' => 'j.e.nt.aiw.o.rl.d.pict.u.r.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '153251','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-12','password' => Hash::make('j.e.nt.aiw.o.rl.d.pict.u.r.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ZuvilsNus','last_name' => 'ZuvilsNus','email' => 'j.e.n.t.ai.w.or.ldp.i.ctu.r.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '132345','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-13','password' => Hash::make('j.e.n.t.ai.w.or.ldp.i.ctu.r.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Elvera Cronin I','last_name' => 'Elvera Cronin I','email' => 'sweetback410@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '471 Abernathy Ridges','city' => '471 Abernathy Ridges','postal' => '471 ABERNATHY RIDGES','state' => '471 Abernathy Ridges','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-16','password' => Hash::make('sweetback410@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'AlvaroCak','last_name' => 'AlvaroCak','email' => 'baranovoleg88@rambler.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Paphos','city' => 'Paphos','postal' => '122431','state' => 'Paphos','country' => 'CY','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-17','password' => Hash::make('baranovoleg88@rambler.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'poliyay','last_name' => 'poliyay','email' => 'gorlina.2006@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '111345','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-17','password' => Hash::make('gorlina.2006@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Derrick ','last_name' => 'D','email' => 'dpeanut685@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '15420 park grove ','city' => 'Detroit ','postal' => '48205','state' => 'Eastern','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-18','password' => Hash::make('dpeanut685@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ZuvilsNus','last_name' => 'ZuvilsNus','email' => 'j.en.ta.iwo.rld.pi.c.tur.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '131335','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-18','password' => Hash::make('j.en.ta.iwo.rld.pi.c.tur.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Raina Stracke','last_name' => 'Raina Stracke','email' => 'nolden53@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '76935 Weimann Crest','city' => '76935 Weimann Crest','postal' => '76935 WEIMANN CREST','state' => '76935 Weimann Crest','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-18','password' => Hash::make('nolden53@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Miss Domenico Nolan','last_name' => 'Miss Domenico Nolan','email' => 'manondouze@ziggo.nl','dob' => NULL,'phone' => '','gender' => NULL,'address' => '86660 Mayer Fords','city' => '86660 Mayer Fords','postal' => '86660 MAYER FORDS','state' => '86660 Mayer Fords','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-20','password' => Hash::make('manondouze@ziggo.nl')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillSog','last_name' => 'InvillSog','email' => 'j.e.nt.ai.worl.dp.ic.tu.r.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '143333','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-25','password' => Hash::make('j.e.nt.ai.worl.dp.ic.tu.r.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'je.n.t.a.i.wo.r.l.dp.i.cturet5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '141333','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-25','password' => Hash::make('je.n.t.a.i.wo.r.l.dp.i.cturet5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillSog','last_name' => 'InvillSog','email' => 'je.n.t.a.iwo.rl.dp.i.c.t.u.ret5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '131534','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-26','password' => Hash::make('je.n.t.a.iwo.rl.dp.i.c.t.u.ret5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'je.nta.iworld.pic.t.u.ret5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '144355','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2020-12-30','password' => Hash::make('je.nta.iworld.pic.t.u.ret5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'poliyay','last_name' => 'poliyay','email' => '32galy@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '111114','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-01','password' => Hash::make('32galy@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dax Krajcik','last_name' => 'Dax Krajcik','email' => 'info@plateaupropertymgmt.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '65845 Michale Shoals','city' => '65845 Michale Shoals','postal' => '65845 MICHALE SHOALS','state' => '65845 Michale Shoals','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-04','password' => Hash::make('info@plateaupropertymgmt.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Monica Conroy','last_name' => 'Monica Conroy','email' => 'donny.carroll2@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '162 Keely Gardens','city' => '162 Keely Gardens','postal' => '162 KEELY GARDENS','state' => '162 Keely Gardens','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-08','password' => Hash::make('donny.carroll2@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillSog','last_name' => 'InvillSog','email' => 'j.ent.ai.wo.rldpi.c.tu.re.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '152425','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-10','password' => Hash::make('j.ent.ai.wo.rldpi.c.tu.re.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'monyya','last_name' => 'monyya','email' => 'shisha71@list.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '113243','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-14','password' => Hash::make('shisha71@list.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'mmsah','last_name' => 'mmsah','email' => 'fjgaly@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '144353','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-19','password' => Hash::make('fjgaly@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Miss Gustave Cronin','last_name' => 'Miss Gustave Cronin','email' => 'j.foord@rcsd.ca','dob' => NULL,'phone' => '','gender' => NULL,'address' => '050 Elvera Fields','city' => '050 Elvera Fields','postal' => '050 ELVERA FIELDS','state' => '050 Elvera Fields','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-21','password' => Hash::make('j.foord@rcsd.ca')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'j.ent.aiw.o.rldpi.c.t.ure.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '114141','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-23','password' => Hash::make('j.ent.aiw.o.rldpi.c.t.ure.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mae Nolan','last_name' => 'Mae Nolan','email' => 'fernandessarah1988@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '84547 Jon Mills','city' => '84547 Jon Mills','postal' => '84547 JON MILLS','state' => '84547 Jon Mills','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-26','password' => Hash::make('fernandessarah1988@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ZuvilsNus','last_name' => 'ZuvilsNus','email' => 'j.ent.aiwo.r.l.dpi.ct.ur.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '114155','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-01-27','password' => Hash::make('j.ent.aiwo.r.l.dpi.ct.ur.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'LeonardoPoh','last_name' => 'LeonardoPoh','email' => 'van00van@meta.ua','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'РњРѕСЃРєРІР°','city' => 'РњРѕСЃРєРІР°','postal' => 'РЊРЅСЃРЄРІР°','state' => 'РњРѕСЃРєРІР°','country' => 'CK','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-01','password' => Hash::make('van00van@meta.ua')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Emanuilichkansonberg','last_name' => 'Emanuilichkansonberg','email' => 'allagra.yarilova@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'РњРѕСЃРєРІР°','city' => 'РњРѕСЃРєРІР°','postal' => 'РЊРЅСЃРЄРІР°','state' => 'РњРѕСЃРєРІР°','country' => 'JO','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-05','password' => Hash::make('allagra.yarilova@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'ZuvilsNus','last_name' => 'ZuvilsNus','email' => 'je.nt.a.i.w.orldpic.turet5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '114251','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-06','password' => Hash::make('je.nt.a.i.w.orldpic.turet5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'mayahh','last_name' => 'mayahh','email' => 'pshalya13@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '155313','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-06','password' => Hash::make('pshalya13@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Dameon Cormier','last_name' => 'Dameon Cormier','email' => 'whitemanphilip4@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '87024 Rice Mountains','city' => '87024 Rice Mountains','postal' => '87024 RICE MOUNTAINS','state' => '87024 Rice Mountains','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-07','password' => Hash::make('whitemanphilip4@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Americo Kling','last_name' => 'Americo Kling','email' => 'bbourgon@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '3768 Sonny Port','city' => '3768 Sonny Port','postal' => '3768 SONNY PORT','state' => '3768 Sonny Port','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-10','password' => Hash::make('bbourgon@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'mayahh','last_name' => 'mayahh','email' => 'smsuhka@mail.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'Al Manamah','city' => 'Al Manamah','postal' => '142513','state' => 'Al Manamah','country' => 'BH','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-10','password' => Hash::make('smsuhka@mail.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Mr. Donna Marks','last_name' => 'Mr. Donna Marks','email' => 'pbalmeda@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6293 Lonny Unions','city' => '6293 Lonny Unions','postal' => '6293 LONNY UNIONS','state' => '6293 Lonny Unions','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-10','password' => Hash::make('pbalmeda@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'j.entaiw.o.r.ld.picturet5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '132152','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-11','password' => Hash::make('j.entaiw.o.r.ld.picturet5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'je.ntai.wo.r.ldpi.ct.u.re.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '145354','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-12','password' => Hash::make('je.ntai.wo.r.ldpi.ct.u.re.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sylvia Crona','last_name' => 'Sylvia Crona','email' => 'dennismbrown@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '39029 Champlin Wall','city' => '39029 Champlin Wall','postal' => '39029 CHAMPLIN WALL','state' => '39029 Champlin Wall','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-12','password' => Hash::make('dennismbrown@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Tracy','last_name' => 'Botica','email' => 'tracyboticaphoto@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '7840 Princes Hwy','city' => 'Narrawong','postal' => '3285','state' => 'VIC','country' => 'AU','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-25','password' => Hash::make('tracyboticaphoto@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Johnpaul Satterfield','last_name' => 'Johnpaul Satterfield','email' => 'lindarob767@att.net','dob' => NULL,'phone' => '','gender' => NULL,'address' => '828 Alexa Harbor','city' => '828 Alexa Harbor','postal' => '828 ALEXA HARBOR','state' => '828 Alexa Harbor','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-25','password' => Hash::make('lindarob767@att.net')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'je.nt.aiw.o.rl.dp.i.ct.uret5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '151435','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-25','password' => Hash::make('je.nt.aiw.o.rl.dp.i.ct.uret5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'j.en.tai.wor.ldpi.ctur.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '133512','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-27','password' => Hash::make('j.en.tai.wor.ldpi.ctur.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Celestino Walsh I','last_name' => 'Celestino Walsh I','email' => 'luisa.vaccarella@hotmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '732 Heidenreich Port','city' => '732 Heidenreich Port','postal' => '732 HEIDENREICH PORT','state' => '732 Heidenreich Port','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-02-28','password' => Hash::make('luisa.vaccarella@hotmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Tom Denesik','last_name' => 'Tom Denesik','email' => 'mia.centeno616@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '00094 Juston Spring','city' => '00094 Juston Spring','postal' => '00094 JUSTON SPRING','state' => '00094 Juston Spring','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-01','password' => Hash::make('mia.centeno616@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'je.n.ta.i.wor.l.d.p.i.ct.ur.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '145311','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-02','password' => Hash::make('je.n.ta.i.wor.l.d.p.i.ct.ur.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Micki ','last_name' => 'Clay ','email' => 'mickiclay@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '333 Obert Street','city' => 'Urbanna','postal' => '23175','state' => 'Virginia','country' => 'US','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-02','password' => Hash::make('mickiclay@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Sdvillarome','last_name' => 'Sdvillarome','email' => 'je.nt.a.iwo.r.ldpi.ct.ur.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '154451','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-04','password' => Hash::make('je.nt.a.iwo.r.ldpi.ct.ur.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Kvvillarome','last_name' => 'Kvvillarome','email' => 'j.en.t.ai.w.or.ldpi.ctur.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '145512','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-04','password' => Hash::make('j.en.t.ai.w.or.ldpi.ctur.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Norma Shanahan','last_name' => 'Norma Shanahan','email' => 'marisa.mcl2@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '04258 Gladyce Haven','city' => '04258 Gladyce Haven','postal' => '04258 GLADYCE HAVEN','state' => '04258 Gladyce Haven','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-05','password' => Hash::make('marisa.mcl2@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jeffery Kuhn IV','last_name' => 'Jeffery Kuhn IV','email' => 'xiongs17@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '49949 Garnet Brook','city' => '49949 Garnet Brook','postal' => '49949 GARNET BROOK','state' => '49949 Garnet Brook','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-11','password' => Hash::make('xiongs17@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Knvpllarome','last_name' => 'Knvpllarome','email' => 'jen.t.a.iwo.r.l.d.p.ic.t.u.r.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '115435','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-11','password' => Hash::make('jen.t.a.iwo.r.l.d.p.ic.t.u.r.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Invillarome','last_name' => 'Invillarome','email' => 'j.e.ntai.w.o.rldp.ic.tur.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '141521','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-11','password' => Hash::make('j.e.ntai.w.o.rldp.ic.tur.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Jdvmllarome','last_name' => 'Jdvmllarome','email' => 'jentaiw.o.r.ld.pi.ct.u.r.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '142325','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-15','password' => Hash::make('jentaiw.o.r.ld.pi.ct.u.r.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Johnathon Quigley','last_name' => 'Johnathon Quigley','email' => 'baby_blue1981@live.ca','dob' => NULL,'phone' => '','gender' => NULL,'address' => '089 Leilani Unions','city' => '089 Leilani Unions','postal' => '089 LEILANI UNIONS','state' => '089 Leilani Unions','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-15','password' => Hash::make('baby_blue1981@live.ca')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Vito Bashirian','last_name' => 'Vito Bashirian','email' => 'jgil4209@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '037 Nella Corner','city' => '037 Nella Corner','postal' => '037 NELLA CORNER','state' => '037 Nella Corner','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-16','password' => Hash::make('jgil4209@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Knvpllarome','last_name' => 'Knvpllarome','email' => 'j.e.n.taiw.or.l.d.p.i.c.tur.et5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '153142','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-18','password' => Hash::make('j.e.n.taiw.or.l.d.p.i.c.tur.et5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'jent.aiw.o.r.l.dpi.ct.ure.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '143313','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-19','password' => Hash::make('jent.aiw.o.r.l.dpi.ct.ure.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'j.entai.worl.d.pi.ct.ur.e.t5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '154345','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-21','password' => Hash::make('j.entai.worl.d.pi.ct.ur.e.t5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Aliza Kessler','last_name' => 'Aliza Kessler','email' => 'samarama2005@gmail.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '95162 Moore Landing','city' => '95162 Moore Landing','postal' => '95162 MOORE LANDING','state' => '95162 Moore Landing','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-22','password' => Hash::make('samarama2005@gmail.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'InvillDaw','last_name' => 'InvillDaw','email' => 'j.e.n.ta.i.w.o.r.ld.pi.c.t.uret5@o5o5.ru','dob' => NULL,'phone' => '','gender' => NULL,'address' => 'London','city' => 'London','postal' => '141222','state' => 'London','country' => 'UM','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-23','password' => Hash::make('j.e.n.ta.i.w.o.r.ld.pi.c.t.uret5@o5o5.ru')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Colin Spinka','last_name' => 'Colin Spinka','email' => 'sade09@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '0908 Carroll Cliffs','city' => '0908 Carroll Cliffs','postal' => '0908 CARROLL CLIFFS','state' => '0908 Carroll Cliffs','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-30','password' => Hash::make('sade09@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;
        $user = User::create([
            'first_name' => 'Duane Altenwerth','last_name' => 'Duane Altenwerth','email' => 'ridethedez@yahoo.com','dob' => NULL,'phone' => '','gender' => NULL,'address' => '6176 Kris Spurs','city' => '6176 Kris Spurs','postal' => '6176 KRIS SPURS','state' => '6176 Kris Spurs','country' => 'AF','avatar_type' => 'storage','active' => '1','confirmed' => '1','timezone' => 'London','created_at' => '2021-03-31','password' => Hash::make('ridethedez@yahoo.com')
        ]);
        $user->save();
        $user->assignRole('student');
        $user = null;

        return route(home_route());
    }

    private function sendAdminMail($user)
    {
        $admins = User::role('administrator')->get();

        foreach ($admins as $admin){
            \Mail::to($admin->email)->send(new AdminRegistered($user));
        }
    }



}
