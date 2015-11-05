<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory {

	/**
	 *
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

		$router[] = new Route('', 'Homepage:default');
		$router[] = new Route('about', 'Homepage:about');
		$router[] = new Route('product/<no>', 'Homepage:detail');
		$router[] = new Route('sign/in', 'Sign:in');
		$router[] = new Route('sign/out', 'Sign:out');
		$router[] = new Route('cart', 'Basket:cart');
		$router[] = new Route('order', 'Basket:order');
		$router[] = new Route('order/success', 'Basket:success');
		$router[] = new Route('account', 'Account:default');
		$router[] = new Route('account/orders', 'Account:orders');
		$router[] = new Route('account/order/<no>', 'Account:orderDetail');
		$router[] = new Route('account/ratings', 'Account:ratings');
		$router[] = new Route('account/rating/<no>', 'Account:rating');

		//$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;
	}

}
