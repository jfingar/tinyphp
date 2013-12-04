tinyphp
=======

Lightweight PHP Application Framework


** Official Documentation Coming Soon (Maybe) **

1. Setup Virtual Host for your project that point to the webroot folder

2. Default routing is identical to Zend Framework, with the exception that the functions called don't end in "Action".
	Examples:
	www.mydomain.com/my-cool-page -> MyCoolPageController.php :: class MyCoolPageController :: protected function index()
	www.mydomain.com/another-page/hello -> AnotherPageController.php :: class AnotherPageController :: protected function hello()

3. Custom Routes may be added to CustomRoutes.php, see comments in that file.