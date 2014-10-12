<?php

namespace Jigoshop\Payment;

/**
 * Payment method interface.
 *
 * @package Jigoshop\Payment
 */
interface Method
{
	/**
	 * @return string ID of payment method.
	 */
	public function getId();

	/**
	 * @return string Human readable name of method.
	 */
	public function getName();

	/**
	 * @return bool Whether current method is enabled and able to work.
	 */
	public function isEnabled();

	/**
	 * @return array List of options to display on Payment settings page.
	 */
	public function getOptions();

	/**
	 * Validates and returns properly sanitized options.
	 *
	 * @param $settings array Input options.
	 * @return array Sanitized result.
	 */
	public function validateOptions($settings);

	/**
	 * Renders method fields and data in Checkout page.
	 *
	 * @return string HTML to display.
	 */
	public function render();

	/**
	 * @param mixed $order Order to process payment for.
	 * @return bool Is processing successful?
	 */
	public function process($order);
}
