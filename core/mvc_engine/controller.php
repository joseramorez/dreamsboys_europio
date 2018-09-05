<?php
/**
* ...
*
* @package    EuropioEngine
* @subpackage MVCEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('core.mvc_engine.helper');


abstract class Controller {

    public function __construct($resource='', $arg='', $api=False) {
        $this->api = $api;
        $this->apidata = '';
        $this->model = MVCEngineHelper::get_model($this); 
        $this->view = MVCEngineHelper::get_view($this);
        if(method_exists($this, $resource)) {
            call_user_func(array($this, $resource), $arg);
        } else {
            HTTPHelper::go(DEFAULT_VIEW);
        }
    }
}

?>
