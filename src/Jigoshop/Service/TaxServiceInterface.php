<?php
namespace Jigoshop\Service;

use Jigoshop\Entity\Product;
use Jigoshop\Shipping\Method;


/**
 * Service calculating tax value for products.
 *
 * @package Jigoshop\Service
 */
interface TaxServiceInterface
{
	/**
	 * @param $product Product|Product\Purchasable Product to calculate tax for.
	 * @return float Overall tax value.
	 */
	public function calculate(Product $product);

	/**
	 * @param $product Product|Product\Purchasable Product to calculate tax for.
	 * @param $taxClass string Tax class.
	 * @throws Exception When tax class is not found.
	 * @return float Tax value for selected tax class.
	 */
	public function get(Product $product, $taxClass);

	/**
	 * @param $product Product|Product\Purchasable Product to calculate tax for.
	 * @return array List of tax values per tax class.
	 */
	public function getAll(Product $product);

	/**
	 * @param Method $method Method to calculate tax for.
	 * @param $price float Price calculated for current cart.
	 * @return float Overall tax value.
	 */
	public function calculateShipping(Method $method, $price);

	/**
	 * @param Method $method Method to calculate tax for.
	 * @param $price float Price calculated for current cart.
	 * @param $taxClass string Tax class.
	 * @return float Tax value for selected tax class.
	 */
	public function getShipping(Method $method, $price, $taxClass);

	/**
	 * @return array List of available tax classes.
	 */
	public function getClasses();

	/**
	 * @param $taxClass string Tax class to get label for.
	 * @return string Tax class label
	 * @throws Exception When tax class is not found.
	 */
	public function getLabel($taxClass);

	/**
	 * Fetches and returns properly formatted list of tax rules.
	 *
	 * @return array List of rules.
	 */
	public function getRules();

	/**
	 * @param $rule array Rule to save.
	 * @return array Saved rule.
	 */
	public function save(array $rule);

	/**
	 * @param $ids array IDs to preserve.
	 */
	public function removeAllExcept($ids);
}
