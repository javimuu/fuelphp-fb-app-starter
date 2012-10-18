<?php

class Controller_Facebook extends Controller_Base {

    public $template = 'templates/facebook';

    protected $facebook;

    public function before() {
        parent::before();

        if(!$this->facebook)
        {
            $this->facebook = new Facebook(array(
                'appId'  => Config::get('application.facebook.app_id'),
                'secret' => Config::get('application.facebook.secret'),
            ));
        }

        // IE cookie security issue in iframes
        $this->response->set_header('P3P', 'CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
    }

}