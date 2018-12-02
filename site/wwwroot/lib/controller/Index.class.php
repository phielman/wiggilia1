<?php
class controller_Index extends _Controller
{
    protected $layout;
    protected $model;

    function __construct() {
        $this->layout = new _View('layout/index.phtml');
       $this->modelRandom = new model_random;
    }

	function index() {
		$this->layout->people = $this->modelRandom->select();
		return $this->layout;
	}

	function losuj(){
		$view = new _View('wylosowane.phtml');
		$view->person = $this->modelRandom->wybierz_osobe($_POST['losujacy']);
		return $view;

	}


}
?>