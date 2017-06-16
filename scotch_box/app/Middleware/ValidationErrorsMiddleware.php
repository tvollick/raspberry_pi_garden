<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware
{
  public function __invoke($request, $response, $next)
  {

    if (isset($_SESSION['errors'])) {

      // I know we're getting here?
      $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);



      unset($_SESSION['errors']);
    }

    // standard middleware
    $response = $next($request, $response);
    return $response;
  }
}
