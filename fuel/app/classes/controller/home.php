<?php

class Controller_Home extends Controller_Facebook {

	public function before()
	{
		parent::before();
	}

    public function action_index()
    {
        $data = array();

        // get signed request from FB page
        $signed_request = $this->facebook->getSignedRequest();

        if(empty($signed_request['page']))
        {
            // called outside of FB, redirect them
            Response::redirect(Input::protocol() . '://facebook.com/' . Config::get('application.facebook.page') . '/app_' . Config::get('application.facebook.app_id'));
        }
        elseif($signed_request['page']['liked'])
        {
            // connection
            $this->template->content = View::forge('competition/connection/index', $data);
        }
        else
        {
            // non connection
            $this->template->content = View::forge('home/non_connection/index', $data);
        }
    }
}