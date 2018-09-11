<?php
import('appmodules.FuncionHelper');

class welcomeView {

    public function inicio() {
        $str = file_get_contents(STATIC_DIR . "html/welcome/welcome.html");
        print Template('MENU')->show($str);
    }
}

?>
