<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Socialite;

class LoginController extends Controller
{
  /**
   * Redirect the user to the Google authentication page.
   *
   * @return \Illuminate\Http\Response
   */
  public function redirectToProvider()
  {
    return Socialite::driver('google')->scopes('https://www.googleapis.com/auth/youtube.force-ssl')->with(['access_type'=>'offline'])->redirect();
  }

  /**
   * Obtain the user information from Google.
   *
   * @return \Illuminate\Http\Response
   */
  public function handleProviderCallback()
  {
    $user = Socialite::driver('google')->stateless()->user();

    $existingUser = User::where('email', $user->getEmail())->first();

    if ($existingUser) {
      $existingUser->token = $user->token;
      $existingUser->save();

      Auth::login($existingUser);
    } else {
      $newUser = new User;
      $newUser->email = $user->getEmail();
      $newUser->token = $user->token;
      $newUser->save();

      Auth::login($newUser);
    }

    return redirect('/host');
  }

  public function logout()
  {
    Auth::logout();
    return redirect('/');
  }
}

//https://developers.google.com/identity/protocols/OAuth2
//After an application obtains an access token, it sends the token to a Google API in an HTTP authorization header. It is possible to send tokens as URI query-string parameters, but we don't recommend it, because URI parameters can end up in log files that are not completely secure. Also, it is good REST practice to avoid creating unnecessary URI parameter names.

//Access tokens are valid only for the set of operations and resources described in the scope of the token request. For example, if an access token is issued for the Google+ API, it does not grant access to the Google Contacts API. You can, however, send that access token to the Google+ API multiple times for similar operations.
