<?php
use Jigoshop\Helper\Forms;
use Jigoshop\Helper\Product;
use Jigoshop\Helper\Render;

/**
 * @var $messages \Jigoshop\Core\Messages Messages container.
 * @var $content string Contents of cart page
 * @var $cart \Jigoshop\Frontend\Cart Cart object.
 * @var $customer \Jigoshop\Entity\Customer Current customer.
 * @var $shippingMethods array List of available shipping methods.
 * @var $paymentMethods array List of available payment methods.
 * @var $showWithTax bool Whether to show product price with or without tax.
 * @var $alwaysShowShipping bool Whether to always show shipping fields.
 * @var $cartUrl string URL to cart.
 * @var $billingFields array Fields to display as billing fields.
 * @var $differentShipping boolean Whether to use different shipping address.
 * @var $termsUrl string Url to terms and conditions page.
 */
?>
<h1><?php _e('Checkout', 'jigoshop'); ?></h1>
<?php Render::output('shop/messages', array('messages' => $messages)); ?>
<?php echo wpautop(wptexturize($content)); ?>
<form action="" role="form" method="post" id="checkout">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Your address', 'jigoshop'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="row" id="billing-address clearfix">
				<?php foreach($billingFields as $field): ?>
				<div class="col-md-<?php echo $field['columnSize']; ?>">
					<!-- TODO: Proper form validation - maybe it's good idea to use Symfony component? -->
					<?php Forms::field($field['type'], $field); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php if (!$alwaysShowShipping): ?>
			<?php Forms::checkbox(array(
				'label' => __('Different shipping address', 'jigoshop'),
				'name' => 'jigoshop_order[different_shipping]',
				'id' => 'different_shipping',
				'checked' => $differentShipping,
				'size' => 9
			)); ?>
			<?php endif; ?>
			<div class="row clearfix<?php !$differentShipping && !$alwaysShowShipping and print ' not-active'; ?>" id="shipping-address">
				<h4><?php _e('Shipping address', 'jigoshop'); ?></h4>
				<?php foreach($shippingFields as $field): ?>
					<div class="col-md-<?php echo $field['columnSize']; ?>">
						<?php Forms::field($field['type'], $field); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Your order', 'jigoshop'); ?></h3>
		</div>
		<table class="table table-hover">
			<thead>
			<tr>
				<th class="product-thumbnail"></th>
				<th class="product-name"><?php _e('Product Name', 'jigoshop'); ?></th>
				<th class="product-price"><?php _e('Unit Price', 'jigoshop'); ?></th>
				<th class="product-quantity"><?php _e('Quantity', 'jigoshop'); ?></th>
				<th class="product-subtotal"><?php _e('Price', 'jigoshop'); ?></th>
			</tr>
			<?php do_action('jigoshop\checkout\table_head', $cart); ?>
			</thead>
			<tbody>
			<?php foreach($cart->getItems() as $key => $item): /** @var \Jigoshop\Entity\Order\Item $item */ ?>
				<?php Render::output('shop/checkout/item/'.$item->getType(), array('cart' => $cart, 'key' => $key, 'item' => $item, 'showWithTax' => $showWithTax)); ?>
			<?php endforeach; ?>
			<?php do_action('jigoshop\checkout\table_body', $cart); ?>
			</tbody>
			<tfoot>
			<tr id="product-subtotal">
				<th scope="row" colspan="4" class="text-right"><?php _e('Products subtotal', 'jigoshop'); ?></th>
				<td><?php echo Product::formatPrice($cart->getProductSubtotal()); ?></td>
			</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Additional order notes', 'jigoshop'); ?></h3>
		</div>
		<div class="panel-body">
			<?php Forms::textarea(array(
				'label' => '',
				'name' => 'jigoshop_order[note]',
				'rows' => 3,
				'size' => 12,
			)); ?>
		</div>
	</div>
	<div class="panel panel-primary" id="totals">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Totals', 'jigoshop'); ?></h3>
		</div>
		<table class="table">
			<tbody>
				<tr id="shipping-calculator">
					<th scope="row"><?php _e('Shipping', 'jigoshop'); ?></th>
					<td>
						<ul class="list-group" id="shipping-methods">
							<?php foreach($shippingMethods as $method): /** @var $method \Jigoshop\Shipping\Method */ ?>
								<?php Render::output('shop/checkout/shipping/method', array('method' => $method, 'cart' => $cart)); ?>
							<?php endforeach; ?>
						</ul>
					</td>
				</tr>
				<tr id="cart-subtotal">
					<th scope="row"><?php _e('Subtotal', 'jigoshop'); ?></th>
					<td><?php echo Product::formatPrice($cart->getSubtotal()); ?></td>
				</tr>
				<?php foreach ($cart->getTax() as $taxClass => $tax): ?>
					<tr id="tax-<?php echo $taxClass; ?>"<?php $tax == 0 and print ' style="display: none;"'; ?>>
						<th scope="row"><?php echo $cart->getTaxLabel($taxClass); ?></th>
						<td><?php echo Product::formatPrice($tax); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr id="cart-total" class="info">
					<th scope="row"><?php _e('Total', 'jigoshop'); ?></th>
					<td><strong><?php echo Product::formatPrice($cart->getTotal()); ?></strong></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php if(count($paymentMethods) > 0): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Select payment method', 'jigoshop'); ?></h3>
		</div>
		<ul class="list-group" id="payment-methods">
			<?php foreach($paymentMethods as $method): /** @var $method \Jigoshop\Payment\Method */ ?>
				<li class="list-group-item" id="payment-<?php echo $method->getId(); ?>">
					<label>
						<input type="radio" name="jigoshop_order[payment_method]" value="<?php echo $method->getId(); ?>" />
						<?php echo $method->getName(); ?>
					</label>
					<div class="well well-sm">
						<?php $method->render(); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<noscript>
			<style type="text/css">
				.jigoshop form #payment-methods li > div {
					display: block;
				}
			</style>
		</noscript>
	</div>
	<?php endif; ?>
	<?php if (!empty($termsUrl)): ?>
		<?php Forms::checkbox(array(
			'name' => 'terms',
			'label' => sprintf(__('I accept the <a href="%s">Terms &amp; Conditions</a>'), $termsUrl),
			'checked' => false,
		)); ?>
	<?php endif; ?>
	<a class="btn btn-default" href="<?php echo $cartUrl; ?>"><?php _e('Back to cart', 'jigoshop'); ?></a>
	<button class="btn btn-success pull-right clearfix" name="action" value="purchase" type="submit"><?php _e('Purchase', 'jigoshop'); ?></button>
</form>
