<?php
/** For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php */
namespace Mini\Core;

require APP . 'core/CoreFunctions.php';

class Application
{
    /** @var null Parts of the URL, as provided and sanitized by splitURL() */
    private $url = [];

    /** @var null The controller */
    private $url_controller = null;

    /** @var null The method (of the above controller), often also named "action" */
    private $url_action = null;

    /** @var array URL parameters */
    private $url_params = array();

    /**
     * "Start" the application:
     * Analyze the URL elements and calls the according controller/method or the fallback
     */
    public function __construct()
    {
        // create array with URL parts in $url
        $this->splitUrl();

        // check for controller: no controller given ? then load start-page
        if (!$this->url_controller) {

            $this->url_controller = new \Mini\Controller\HomeController();
            $this->url_controller->index();

        } elseif (file_exists(APP . 'Controller/' . ucfirst($this->url_controller) . 'Controller.php')) {
            // here we did check for controller: does such a controller exist ?

            // if so, then load this file and create this controller
            // like \Mini\Controller\CarController
            $controller = "\\Mini\\Controller\\" . ucfirst($this->url_controller) . 'Controller';
            $this->url_controller = new $controller();

            // check for method: does such a method exist in the controller ?
            if (method_exists($this->url_controller, $this->url_action) &&
                is_callable(array($this->url_controller, $this->url_action))) {

                if (!empty($this->url_params)) {
                    // Call the method and pass arguments to it
                    call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
                } else {
                    // If no parameters are given, just call the method without parameters, like $this->home->method();
                    $this->url_controller->{$this->url_action}();
                }

            } else {
                if (strlen($this->url_action) == 0) {
                    // no action defined: call the default index() method of a selected controller
                    $this->url_controller->index();
                } else {
                    $this->url_controller = new \Mini\Controller\ErrorController();
                    $this->url_controller->index();
                }
            }
        } else {
            $this->url_controller = new \Mini\Controller\HomeController();
            if (method_exists($this->url_controller, $this->url[0])) {
                if (isset($this->url_action)) {
                    call_user_func_array(array($this->url_controller, $this->url[0]), array_merge([$this->url_action], $this->url_params));
                } else {
                    call_user_func_array(array($this->url_controller, $this->url[0]), $this->url_params);
                }
            } else {
                $this->url_controller = new \Mini\Controller\ErrorController();
                $this->url_controller->index();
            }
        }
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // split URL
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $this->url = explode('/', $url);

            // Put URL parts into according properties
            // By the way, the syntax here is just a short form of if/else, called "Ternary Operators"
            // @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
            $this->url_controller = isset($this->url[0]) ? $this->url[0] : null;
            $this->url_action = isset($this->url[1]) ? $this->url[1] : null;

            // Store the URL params
            $this->url_params = array_slice($this->url, 2);

            // for debugging. uncomment this if you have problems with the URL
            //echo 'Controller: ' . $this->url_controller . '<br>';
            //echo 'Action: ' . $this->url_action . '<br>';
            //echo 'Parameters: ' . print_r($this->url_params, true) . '<br>';
        }
    }
}
