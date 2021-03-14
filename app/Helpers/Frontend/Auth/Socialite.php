<?php

namespace App\Helpers\Frontend\Auth;

/**
 * Class Socialite.
 */
class Socialite
{
    /**
     * Generates social login links based on what is enabled.
     *
     * @return string
     */
    public function getSocialLinks()
    {
        $socialite_enable = [];
        $socialite_links = '';

        if (config('services.facebook.active')) {
            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'facebook')."' class='btn btn-sm btn-info text-white  p-1 px-2  m-1 my-3'><i class='fab fa-facebook'></i></a>";
        }


        if (config('services.google.active')) {
            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'google')."' class='btn btn-sm btn-info text-white   p-1 px-2  m-1 my-3'><i class='fab fa-google'></i></a>";
        }

        if (config('services.twitter.active')) {
            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'twitter')."' class='btn btn-sm btn-info text-white   p-1 px-2  m-1 my-3'><i class='fab fa-twitter'></i></a>";
        }

        if (config('services.linkedin.active')) {
            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'linkedin')."' class='btn btn-sm btn-info text-white   p-1 px-2  m-1 my-3'><i class='fab fa-linkedin'></i></a>";
        }


        if (config('services.bitbucket.active')) {
            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'bitbucket')."' class='btn btn-sm btn-info text-white  p-1 m-1 my-3'><i class='fab fa-bitbucket'></i></a>";
        }


        if (config('services.github.active')) {
            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'github')."' class='btn btn-sm btn-info text-white   p-1 px-2  m-1 my-3'><i class='fab fa-github'></i></a>";
        }


        if ($count = count($socialite_enable)) {
            $socialite_links .= '<div class="alt-text text-center mb-0"><a href="#">SIGN IN WITH</a></div>';
        }

        for ($i = 0; $i < $count; $i++) {
            $socialite_links .= ($socialite_links != '' ? ' ' : '').$socialite_enable[$i];
        }

        return $socialite_links;
    }

    public function getSocialLinks2()
    {
        $socialite_enable = [];
        $socialite_links = '';

        if (config('services.facebook.active')) {
            $socialite_enable[] = "<li><a href='".route('frontend.auth.social.login', 'facebook')."' class=\"icoFacebook\"  ><i class=\"fa fa-facebook-f\"></i></a></li>";
        }

        if (config('services.google.active')) {
            $socialite_enable[] = "<li><a href='".route('frontend.auth.social.login', 'google')."' class=\"icoGoogle\"  ><i class=\"fa fa-google\"></i></a></li>";
        }

//        $socialite_enable[] = "<li><a href='#' class=\"icoInstagram\"  ><i class=\"fa fa-instagram\"></i></a></li>";

//
//
//        if (config('services.twitter.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'twitter')."' ><img src='".asset("assets_new/images/twitter-icon.jpg")."' alt='image' /></a>";
//        }
//
//        if (config('services.linkedin.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'linkedin')."' ><img src='".asset("assets_new/images/linkedin-icon.jpg")."' alt='image' /></a>";
//        }
//
//
//        if (config('services.bitbucket.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'bitbucket')."' ><img src='".asset("assets_new/images/bitbucket-icon.jpg")."' alt='image' /></a>";
//        }
//
//
//        if (config('services.github.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'github')."' ><img src='".asset("assets_new/images/github-icon.jpg")."' alt='image' /></a>";
//        }


        if ($count = count($socialite_enable)) {
            $socialite_links .= '<ul class="social-network social-circle">';
        }

        for ($i = 0; $i < $count; $i++) {
            $socialite_links .= ($socialite_links != '' ? ' ' : '').$socialite_enable[$i];
        }

        if ($count > 0) {
            $socialite_links .= '</ul>';
        }

        return $socialite_links;
    }

    public function getSocialLinksForSignup()
    {
        $socialite_enable = [];
        $socialite_links = '';

        $divHead = '<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 clearfix"><div class="form-group">';
        $divTail = '</div></div>';

        if (config('services.facebook.active')) {
            $socialite_enable[] = $divHead."<a href='".route('frontend.auth.social.login', 'facebook')."' ><img src='".asset("assets_new/images/facebook.jpg")."' alt='image' /></a>".$divTail;
        }


        if (config('services.google.active')) {
            $socialite_enable[] = $divHead."<a href='".route('frontend.auth.social.login', 'google')."' ><img src='".asset("assets_new/images/google-btn.png")."' alt='image' /></a>".$divTail;
        }
//
//        if (config('services.twitter.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'twitter')."' ><img src='".asset("assets_new/images/twitter-icon.jpg")."' alt='image' /></a>";
//        }

//        if (config('services.linkedin.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'linkedin')."' ><img src='".asset("assets_new/images/linkedin-icon.jpg")."' alt='image' /></a>";
//        }
//
//
//        if (config('services.bitbucket.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'bitbucket')."' ><img src='".asset("assets_new/images/bitbucket-icon.jpg")."' alt='image' /></a>";
//        }

//        if (config('services.bitbucket.active')) {
//            $socialite_enable[] = $divHead."<a href='#' ><img src='".asset("assets_new/images/instagram.jpg")."' alt='image' /></a>".$divTail;
//        }


//        if (config('services.github.active')) {
//            $socialite_enable[] = "<a href='".route('frontend.auth.social.login', 'github')."' ><img src='".asset("assets_new/images/github-icon.jpg")."' alt='image' /></a>";
//        }


        if ($count = count($socialite_enable)) {
//            $socialite_links .= '<div class="imgarea clearfix">';
        }

        for ($i = 0; $i < $count; $i++) {
            $socialite_links .= ($socialite_links != '' ? ' ' : '').$socialite_enable[$i];
        }

//        if ($count > 0) {
//            $socialite_links .= '</div>';
//        }

        return $socialite_links;
    }

    /**
     * List of the accepted third party provider types to login with.
     *
     * @return array
     */
    public function getAcceptedProviders()
    {
        return [
            'bitbucket',
            'facebook',
            'google',
            'github',
            'linkedin',
            'twitter',
        ];
    }
}
