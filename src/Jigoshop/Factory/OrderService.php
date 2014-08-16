<?php

namespace Jigoshop\Factory;

use Jigoshop\Core\Options;
use Jigoshop\Service\Cache\Order\Simple as SimpleCache;
use Jigoshop\Service\Order as Service;
use Jigoshop\Service\OrderServiceInterface;
use WPAL\Wordpress;

class OrderService
{
	/** @var \WPAL\Wordpress */
	private $wp;
	/** @var \Jigoshop\Core\Options */
	private $options;

	public function __construct(Wordpress $wp, Options $options)
	{
		$this->wp = $wp;
		$this->options = $options;
	}

	/**
	 * @return OrderServiceInterface Orders service.
	 * @since 2.0
	 */
	public function getService()
	{
		/** @var \WPAL\Wordpress $wp */
		$service = new Service($this->wp);

		switch ($this->options->get('cache_mechanism')) {
			case 'simple':
				$service = new SimpleCache($service);
				break;
			default:
				$service = $this->wp->applyFilters('jigoshop\core\get_order_service', $service);
		}

		return $service;
	}
}