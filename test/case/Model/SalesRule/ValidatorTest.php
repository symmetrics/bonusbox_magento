<?php
class Model_ValidatorTest extends MagentoTest
{
	public function testBonusboxCouponValid()
	{
		$this->applyCoupon($quote = $this->getQuote(), $code = Stub_Model_Client_Coupons::SILVER);
		$this->assertEquals($code, $quote->getCouponCode());
 		$this->assertEquals($code, $quote->getShippingAddress()->getDiscountDescription());
		$this->assertContains(2, explode(',', $quote->getAppliedRuleIds()));
	}

	
	public function testBonusboxCouponValidWithCombination()
	{
		$this->applyCoupon($quote = $this->getQuote(), $code = Stub_Model_Client_Coupons::GOLD);
		$this->assertEquals($code, $quote->getCouponCode());
		$this->assertContains(3, explode(',', $quote->getAppliedRuleIds()));
	}
	
	
	public function testBonusboxCouponInvalid()
	{
		$this->applyCoupon($quote = $this->getQuote(), Stub_Model_Client_Coupons::INVALID_RULE);
		$this->assertEmpty($quote->getCouponCode());
	}
	
	
	/**
	 * Make sure that regular logic is not broken
	 */
	public function testRegularCouponValid()
	{
 		$this->applyCoupon($quote = $this->getQuote(), $code = 'Regular');
 		$this->assertEquals($code, $quote->getCouponCode());
 		$this->assertEquals($code, $quote->getShippingAddress()->getDiscountDescription());
 		$this->assertContains(4, explode(',', $quote->getAppliedRuleIds()));
	}

	
	/**
	 * Ensure that regular logic is not broken
	 */
	public function testRegularCouponInvalid()
	{
		$this->applyCoupon($quote = $this->getQuote(), 'SpecialInvalidCode');
		$this->assertEmpty($quote->getCouponCode());
 		$this->assertEmpty($quote->getShippingAddress()->getDiscountDescription());
	}
	
	
	public function testCouponEmpty()
	{
		$this->applyCoupon($quote = $this->getQuote(), null);
		$this->assertEmpty($quote->getCouponCode());
	}
}