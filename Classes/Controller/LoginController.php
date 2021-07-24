<?php


namespace Mblunck\Registration\Controller;

/**
 * Class LoginController
 * @package Mblunck\Registration\Controller
 */
class LoginController extends ActionController
{

    public function showAction() :void
    {
      $this->view->assignMultiple([
          'settings' => $this->settings,
      ]);
    }
}
