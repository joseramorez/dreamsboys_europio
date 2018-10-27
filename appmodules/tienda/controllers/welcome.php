<?php

import('appmodules.tienda.models.welcome');
import('appmodules.tienda.views.welcome');

class welcomeController extends Controller {

  public function inicio()
  {
    @SessionHandler()->check_state();
    $this->view->inicio();
  }
}

?>
