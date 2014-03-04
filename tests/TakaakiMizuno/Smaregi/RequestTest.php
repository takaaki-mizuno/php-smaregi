<?php

$superGlobals = array();

class RequestTest extends \PHPUnit_Framework_TestCase
{

	function getRandomId() {
		$str = "";
		for ($i = 0, $str = null; $i < 10; ) {
			$num = mt_rand(0x30, 0x7A);
			if ((0x30 <= $num && $num <= 0x39) || (0x41 <= $num && $num <= 0x5A)
				|| (0x61 <= $num && $num <= 0x7A)) {
				$str .= chr($num);
				$i++;
			}
		}
		return $str;
	}

	public function setUp() {
		$this->smaregiapi = new TakaakiMizuno\Smaregi\Request
            (
             "ACCESS_TOKEN",
             "CLIENT_ID",
             "END_POINT"
             );
	}

	public function testGetCategories() {
		$response = $this->smaregiapi->getCategories();
		$this->assertEquals(true, $response->isSuccess(), "getCategories failed");
		$this->assertArrayHasKey("result", $response->responseJson, "getCategories didn't return result");
		$this->assertArrayHasKey("total_count", $response->responseJson, "getCategories didn't return total_count");
		$this->assertGreaterThan(0, $response->responseJson['total_count'], "Total count of result is zero");
		$this->assertCount((integer)$response->responseJson['total_count'], $response->responseJson['result'], "Get Samecount");
		$this->assertArrayHasKey("categoryId", $response->responseJson['result'][0], "getCategories didn't return total_count");
	}

	public function testUpdateCategories() {
		global $superGlobals;
		$superGlobals['categoryId'] = (string)time()%10000 + 50000;
		$data =
			[
			 "categoryId"   => $superGlobals['categoryId'],
			 "categoryName" => "Test Category",
			 ];

		$response = $this->smaregiapi->updateCategories($data);
		$this->assertEquals(true, $response->isSuccess(), "updateCategories failed");
		$this->assertArrayHasKey("result", $response->responseJson, "updateCategories didn't return result");
		$this->assertEquals(1, $response->responseJson['result']['Category']);
	}

	public function testGetProducts() {
		$response = $this->smaregiapi->getProducts("Product");
		$this->assertEquals(true, $response->isSuccess(), "getProducts failed");
		$this->assertArrayHasKey("result", $response->responseJson, "getProducts didn't return result");
		$this->assertArrayHasKey("total_count", $response->responseJson, "getProducts didn't return total_count");
		$this->assertGreaterThan(0, $response->responseJson['total_count'], "Total count of result is zero");
		$this->assertCount((integer)$response->responseJson['total_count'], $response->responseJson['result'], "Get Samecount");
		$this->assertArrayHasKey("productId", $response->responseJson['result'][0], "getProducts didn't return total_count");

	}

	public function testUpdateProducts() {
		global $superGlobals;
		$superGlobals['productId'] = (string)time();
		$data =
			[
			 "productId"   => $superGlobals['productId'],
			 "categoryId"  => "4",
			 "productName" => "Test Product",
			 "price"       => 1000
			 ];

		$response = $this->smaregiapi->updateProducts("Product", $data);
		$this->assertEquals(true, $response->isSuccess(), "updateProducts failed");
		$this->assertArrayHasKey("result", $response->responseJson, "updateProducts didn't return result");
		$this->assertEquals(1, $response->responseJson['result']['Product']);
	}

	public function testGetStocks() {
		$response = $this->smaregiapi->getStocks();
		$this->assertEquals(true, $response->isSuccess(), "getStocks failed");
		$this->assertArrayHasKey("result", $response->responseJson, "getStocks didn't return result");
		$this->assertArrayHasKey("total_count", $response->responseJson, "getStocks didn't return total_count");
		$this->assertGreaterThan(0, $response->responseJson['total_count'], "Total count of result is zero");
		$this->assertCount((integer)$response->responseJson['total_count'], $response->responseJson['result'], "Get Some Stocks");
		$this->assertArrayHasKey("storeId", $response->responseJson['result'][0], "getStocks didn't return total_count");
		$this->assertArrayHasKey("productId", $response->responseJson['result'][0], "getStocks didn't return total_count");
	}

	public function testUpdateStocks() {
		global $superGlobals;
		$superGlobals['customerId'] = (string)time()%10000 + 60000;
		$data =
			[
			 "storeId"       => "1",
			 "productId"     => $superGlobals['productId'],
			 "stockAmount"   => 10,
			 "stockDivision" => "15"
			 ];
		$response = $this->smaregiapi->updateStocks($data);
		$this->assertEquals(true, $response->isSuccess(), "updateStocks failed");
		$this->assertArrayHasKey("result", $response->responseJson, "updateStocks didn't return result");
		$this->assertArrayHasKey("Stock", $response->responseJson['result'], "updateStocks didn't return Stock");
		$this->assertEquals(1, $response->responseJson['result']['Stock']);
	}

	public function testGetCustomers() {
		$response = $this->smaregiapi->getCustomers();
		$this->assertEquals(true, $response->isSuccess(), "getCustomers failed");
		$this->assertArrayHasKey("result", $response->responseJson, "getCustomers didn't return result");
		$this->assertArrayHasKey("total_count", $response->responseJson, "getCustomers didn't return total_count");
		$this->assertGreaterThan(0, $response->responseJson['total_count'], "Total count of result is zero");
		$this->assertCount((integer)$response->responseJson['total_count'], $response->responseJson['result'], "Get Some Customers");
		$this->assertArrayHasKey("customerId", $response->responseJson['result'][0], "getCustomers didn't return total_count");
	}

	public function testUpdateCustomers() {
		global $superGlobals;
		$superGlobals['customerId'] = (string)time()%10000 + 60000;
		$data =
			[
			 "customerId"      => $superGlobals['customerId'],
			 "customerCode"    => $superGlobals['customerId'],
			 "lastName"        => "Test",
			 "firstName"       => "User",
			 "lastKana"        => "Kana",
			 "firstKana"       => "Kana",
			 "sex"             => "0",
			 "mailReceiveFlag" => "0",
			 "status"          => "0",
             "point"           => 200,
			 ];

		$response = $this->smaregiapi->updateCustomers($data);
		$this->assertEquals(true, $response->isSuccess(), "updateCustomers failed");
		$this->assertArrayHasKey("result", $response->responseJson, "updateCustomers didn't return result");
		$this->assertArrayHasKey("Customer", $response->responseJson['result'], "updateCustomers didn't return Customer");
		$this->assertEquals(1, $response->responseJson['result']['Customer']);
	}

    public function testUpdateOrder() {
		global $superGlobals;
        $date = (new DateTime("now", new DateTimeZone("Asia/Tokyo")))->format("Y-m-d");
        $datetime = (new DateTime("now", new DateTimeZone("Asia/Tokyo")))->format("Y-m-d H:i:s");
        print $date;
        $orderHead =
            [
             "transactionHeadDivision" => "1",
             "cancelDivision"          => "0",
             "subtotal"                => "1000",
             "pointDiscount"           => "100",
             "total"                   => "900",
             "storeId"                 => "1",
             "terminalId"              => "9999",
             "terminalTranId"          => "9999",
             "sumDivision"             => "2",
             "sumDateTime"             => $date,
             "terminalTranDateTime"    => $datetime,
             ];
        $orderDetails =
            [
             [
              "transactionDetailDivision" => "1",
              "productId"                 => $superGlobals['productId'],
              "salesPrice"                => 1000,
              "quantity"                  => 1,
              ]
             ];
        $response = $this->smaregiapi->updateOrders($orderHead, $orderDetails);
        print_r($response->responseJson);
		$this->assertEquals(true, $response->isSuccess(), "update Order failed");
		$this->assertArrayHasKey("result", $response->responseJson, "deleteOrder didn't return result");
		$this->assertArrayHasKey("TransactionHead", $response->responseJson['result'], "updateOrder didn't return TransactionHead");
		$this->assertEquals(1, $response->responseJson['result']['TransactionHead']);
		$this->assertArrayHasKey("TransactionDetail", $response->responseJson['result'], "updateOrder didn't return TransactionDetail");
		$this->assertEquals(1, $response->responseJson['result']['TransactionDetail']);
    }

	public function testDeleteCustomers() {
		global $superGlobals;
		$response = $this->smaregiapi->deleteCustomers($superGlobals['customerId']);
		$this->assertEquals(true, $response->isSuccess(), "deleteCustomers failed");
		$this->assertArrayHasKey("result", $response->responseJson, "deleteCustomers didn't return result");
		$this->assertArrayHasKey("Customer", $response->responseJson['result'], "deleteCustomers didn't return Customer");
		$this->assertEquals(1, $response->responseJson['result']['Customer']);
	}

	public function testDeleteStocks() {
		global $superGlobals;
		$response = $this->smaregiapi->deleteStocks("1", $superGlobals['productId']);
		$this->assertEquals(true, $response->isSuccess(), "deleteStocks failed");
		$this->assertArrayHasKey("result", $response->responseJson, "deleteStocks didn't return result");
		$this->assertArrayHasKey("Stock", $response->responseJson['result'], "deleteStocks didn't return Stock");
		$this->assertEquals(1, $response->responseJson['result']['Stock']);
	}

	public function testDeleteProducts() {
		global $superGlobals;
		$response = $this->smaregiapi->deleteProducts("Product", $superGlobals['productId']);
		$this->assertEquals(true, $response->isSuccess(), "deleteProducts failed");
		$this->assertArrayHasKey("result", $response->responseJson, "deleteProducts didn't return result");
		$this->assertEquals(1, $response->responseJson['result']['Product']);
	}

	public function testDeleteCategories() {
		global $superGlobals;
		$response = $this->smaregiapi->deleteCategories($superGlobals['categoryId']);
		$this->assertEquals(true, $response->isSuccess(), "deleteCategories failed");
		$this->assertArrayHasKey("result", $response->responseJson, "deleteCategories didn't return result");
		$this->assertEquals(1, $response->responseJson['result']['Category']);
	}

}

?>