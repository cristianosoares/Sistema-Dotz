<?php


class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->first_name.' '.$auth->getIdentity()->last_name;
            $logoutUrl = $this->view->url(array('controller'=>'auth',
                'action'=>'logout'), null, true);
				
            //return 'Welcome ' . $username .  '. <a href="'.$logoutUrl.'">Logout</a>';
			
			return '<p>
    <strong>Welcome ' . $username .  '!</strong><br />
	    <a href="#"><strong>Profile</strong></a><strong> | <a href="#">Change Settings</a> | <a href="'.$logoutUrl.'">Logout</a></strong></p>';
			//echo'<pre>';
			//return print_r($auth->getIdentity());
        } 

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'));
        return '<a href="'.$loginUrl.'">Login</a>';
    }
}
