<?php
namespace Myu\App\Controllers;

use Myu\Components\Routing\Controller;

/**
 * Home Controller
 */
class HomeController extends Controller
{
	public function index($request, $response)
	{
		$this->render('home.php');
	}
}