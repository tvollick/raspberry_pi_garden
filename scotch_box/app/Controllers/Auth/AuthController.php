<?php

namespace App\Controllers\Auth;
use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function getSignUp($request, $response)
    {
      // render view
      return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp ($request, $response)
    {
      $this->handleSignup($request, $response);

      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    public function apiPostSignUp ($request, $response)
    {
      $this->handleSignup($request, $response);

      return $response->withStatus(200);
    }

    private function handleSignup ($request, $response)
    {
      $validation = $this->validator->validate($request, [
        'email' => v::noWhitespace()->notEmpty(),
        'name' => v::notEmpty()->alpha(),
        'phone' => v::notEmpty(),
        'password' => v::noWhitespace()->notEmpty()
      ]);

      if ($validation->failed()) {
        // redirect back
        return $response->withRedirect($this->router->pathFor('auth.signup'));
      }

      $user = User::create([
        'email' => $request->getParam('email'),
        'name' => $request->getParam('name'),
        'phone' => $request->getParam('phone'),
        'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
      ]);
    }
}
