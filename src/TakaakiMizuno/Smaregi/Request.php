<?php

namespace TakaakiMizuno\Smaregi;

class Request {
    private static $HEADER_CONTACT_ID = "X_contract_id";
    private static $HEADER_ACCESS_TOKEN = "X_access_token";

    function __construct($accessToken, $contactId, $endPoint) {
        $this->accessToken = $accessToken;
        $this->contactId = $contactId;
        $this->endPoint  = $endPoint;
        $this->params = [
						 "conditions" => [],
						 "fields"     => [],
						 "order"      => [],
						 ];
    }

    function __destruct() {
    }

    function _addParams($name, $data) {
        if ( is_array($data) ){
            $this->params[$name] = array_merge( $this->params[$name], $data );
        }else{
            $this->params[$name][] = $data;
        }
    }

    function conditions($data) {
        $this->_addParams("conditions", $data);
        return $this;
	}

    function fields($data) {
        $this->_addParams("fields", $data);
        return $this;
	}

    function order($data) {
        $this->_addParams("order", $data);
        return $this;
	}

    function update($procName, $tableName, $data) {
		$data = [
				 "proc_info" =>
				 [
				  "proc_division" => "U",
				  "proc_detail_division" => "1",
				  ],
				 "data" =>
				 [
				  [
				   "table_name" => $tableName,
				   "rows" => [ $data ]
				   ]
				  ]
				 ];
		return $this->accessApi($procName, $data);
    }

    function updates($procName, $data) {
		$data = [
				 "proc_info" =>
				 [
				  "proc_division" => "U",
				  "proc_detail_division" => "1",
				  ],
				 "data" => $data,
				 ];
		return $this->accessApi($procName, $data);
    }

    function delete($procName, $tableName, $data) {
		$data = [
				 "proc_info" =>
				 [
				  "proc_division" => "D",
				  ],
				 "data" =>
				 [
				  [
				   "table_name" => $tableName,
				   "rows" => [ $data ]
				   ]
				  ]
				 ];
		return $this->accessApi($procName, $data);
    }

    function get($procName, $tableName, $limit=0, $page=0) {
        $data = [
                 'table_name' => $tableName
                 ];
        if( $page > 0 ){
            $data['page'] = $page;
        }
        if( $limit > 0 ){
            $data['limit'] = $limit;
        }
        foreach (array("conditions","fields","order") as $param) {
            if( count($this->params[$param]) > 0 ){
                $data[$param] = $this->params[$param];
            }
        }
        return $this->accessApi($procName, $data);
    }

    function accessApi($proc_name, $data) {
        $response = \Unirest::post
            (
             $this->endPoint,
             array(
                   self::$HEADER_CONTACT_ID   => $this->contactId,
                   self::$HEADER_ACCESS_TOKEN => $this->accessToken,
                   ),
             array(
                   "proc_name" => $proc_name,
                   "params"    => json_encode($data),
                   )
             );
        return new Response( $response );
    }

    function getProducts($tableName, $limit=0, $page=0) {
		return $this->get("product_ref", $tableName, $limit, $page);
    }

    function updateProducts($tableName, $data) {
		return $this->update("product_upd", $tableName, $data);
    }

    function deleteProducts($tableName, $productId) {
		$data =
			[
			 "productId"   => $productId
			 ];
		return $this->delete("product_upd", $tableName, $data);
    }

    function getCategories($limit=0, $page=0) {
		return $this->get("category_ref", "Category", $limit, $page);
    }

    function updateCategories($data) {
		return $this->update("category_upd", "Category", $data);
    }

    function deleteCategories($categoryId) {
		$data =
			[
			 "categoryId"   => $categoryId
			 ];
		return $this->delete("category_upd", "Category", $data);
    }

    function getCustomers($limit=0, $page=0) {
		return $this->get("customer_ref", "Customer", $limit, $page);
    }

    function updateCustomers($data) {
		return $this->update("customer_upd", "Customer", $data);
    }

    function deleteCustomers($customerId) {
		$data =
			[
			 "customerId"   => $customerId
			 ];
		return $this->delete("customer_upd", "Customer", $data);
    }

    function getStocks($limit=0, $page=0) {
		return $this->get("stock_ref", "Stock", $limit, $page);
    }

    function updateStocks($data) {
		return $this->update("stock_upd", "Stock", $data);
    }

    function deleteStocks($storeId, $productId) {
		$data =
			[
			 "storeId" => $storeId,
			 "productId" => $productId,
			 "stockAmount" => 0,
			 "stockDivision" => "15",
			 ];
		return $this->update("stock_upd", "Stock", $data);
    }

    function updateOrders($orderHead, $orderDetails){
		return $this->updates("transaction_upd",
                              [
                               [
                                "table_name" => "TransactionHead",
                                "rows"       => [ $orderHead ],
                                ],
                               [
                                "table_name" => "TransactionDetail",
                                "rows"       => $orderDetails,
                                ]
                               ]
                              );
    }
}
