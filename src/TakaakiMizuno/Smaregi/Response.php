<?php

namespace TakaakiMizuno\Smaregi;

class Response {

    public static $ERROR_NO_ERROR                      = 0;
    public static $ERROR_INVALID_REQUEST_DATA          = 11;
    public static $ERROR_INVALID_ACCESS_TOKEN          = 13;
    public static $ERROR_INVALID_CONTACT_ID            = 14;
    public static $ERROR_INVALID_PROCESS_NAME          = 15;
    public static $ERROR_AUTHENTICATION_FAILED         = 21;
    public static $ERROR_ACCOUNT_LOCKED                = 22;
    public static $ERROR_FUNCTIONALITY_DISABLED        = 23;
    public static $ERROR_UNKNOWN_IP_ADDRESS            = 24;
    public static $ERROR_INVALID_TABLE_NAME            = 31;
    public static $ERROR_INVALID_PROC_DIVISION         = 32;
    public static $ERROR_INVALID_PROC_DETAIL_DIVISION  = 33;
    public static $ERROR_INVALID_INPUT_DATA            = 41;
    public static $ERROR_INPUT_DATA_ALREADY_EXISTS     = 42;
    public static $ERROR_DATA_EXTRACTION_FAILED        = 43;

    public static $ERROR_WRONG_TABLE_NAME              = 101;
    public static $ERROR_WRONG_PARAMETERS              = 102;
    public static $ERROR_UNKNOWN                       = 999;


    function __construct($response) {
        $this->response = null;
        $this->statusCode = 0;
        $this->responseJson = null;
        $this->errorText = null;
        $this->errorNo = self::$ERROR_NO_ERROR;
        if( $response ){
            $this->response = $response;
            $this->statusCode = $this->response->code;
            $this->responseJson = json_decode($response->raw_body, true);
            if( !$this->isSuccess() ){
                if( array_key_exists("error_code", $response->body ) ){
                    $this->errorCode = $response->body["error_code"];
                }else{
                    $this->errorCode = self::$ERROR_UNKNOWN;
                }
            }
        }
    }

    function __destruct() {
    }

    function isSuccess() {
      return ( $this->statusCode >= 200 && $this->statusCode <= 299 ) ? true : false;
    }

    // Static Methods
    public static function getErrorResponse($errorNo, $errorText) {
      $response = new SmaregiAPIResponse( Null );
      $response->errorNo = $errorNo;
      $response->errorText = $errorText;
      return $response;
    }

}
