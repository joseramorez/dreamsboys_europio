<?php

import('appmodules.tienda.models.welcome');
import('appmodules.tienda.views.welcome');

class welcomeController extends Controller {

  public function inicio()
  {
    $this->view->inicio();
  }
}

?>
