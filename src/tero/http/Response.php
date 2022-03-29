<?php
namespace tero\http;

/**
 * Class Response
 *
 * @package tero\http
 *
 * @method static OK
 * @method static Created
 * @method static Accepted
 * @method static NoContent
 * @method static ResetContent
 * @method static PartialContent
 * @method static MultipleChoices
 * @method static MovedPermanently
 * @method static Found
 * @method static SeeOther
 * @method static NotModified
 * @method static UseProxy
 * @method static TemporaryRedirect
 * @method static BadRequest
 * @method static Unauthorized
 * @method static PaymentRequired
 * @method static Forbidden
 * @method static NotFound
 * @method static MethodNotAllowed
 * @method static NotAcceptable
 * @method static ProxyAuthenticationRequired
 * @method static RequestTimeout
 * @method static Conflict
 * @method static Gone
 * @method static LengthRequired
 * @method static PreconditionFailed
 * @method static RequestEntityTooLarge
 * @method static UnsupportedMediaType
 * @method static RequestedRangeNotSatisfiable
 * @method static ExpectationFailed
 * @method static InternalServerError
 * @method static NotImplemented
 * @method static BadGateway
 * @method static ServiceUnavailable
 * @method static GatewayTimeout
 * @method static HTTPVersionNotSupported
 */
class Response{
	public static $code = 200;
	public static $contentType;

	private static $responseCodes = array(
		// successful 2xx
		200 => "OK", 
		201 => "Created", 
		202 => "Accepted", 
		203 => "Non-Authoritative Information", 
		204 => "No Content", 
		205 => "Reset Content", 
		206 => "Partial Content", 
 
		//  redirection 3xx
		300 => "Multiple Choices", 
		301 => "Moved Permanently", 
		302 => "Found", 
		303 => "See Other", 
		304 => "Not Modified", 
		305 => "Use Proxy", 
		306 => "(Unused)", 
		307 => "Temporary Redirect", 
 
		//  client error 4xx
		400 => "Bad Request", 
		401 => "Unauthorized", 
		402 => "Payment Required", 
		403 => "Forbidden", 
		404 => "Not Found", 
		405 => "Method Not Allowed", 
		406 => "Not Acceptable", 
		407 => "Proxy Authentication Required", 
		408 => "Request Timeout", 
		409 => "Conflict", 
		410 => "Gone", 
		411 => "Length Required", 
		412 => "Precondition Failed", 
		413 => "Request Entity Too Large", 
		414 => "Request-URI Too Long", 
		415 => "Unsupported Media Type", 
		416 => "Requested Range Not Satisfiable", 
		417 => "Expectation Failed", 
 
		//  server error 5xx
		500 => "Internal Server Error", 
		501 => "Not Implemented", 
		502 => "Bad Gateway", 
		503 => "Service Unavailable", 
		504 => "Gateway Timeout", 
		505 => "HTTP Version Not Supported" 
	);

	public static function create($httpCode, $httpMessage = null, $body = null){
		if(is_null($httpMessage)){
			$httpMessage = self::$responseCodes[$httpCode];
		}

		// set the http code
		self::$code = $httpCode;

		// set the headers
		header("HTTP/1.1 {$httpCode} {$httpMessage}", true, $httpCode);

		// output the body, if needed
		if(!is_null($body)){
			echo $body;
		}
	}

    /**
     * As of PHP 5.3.0
     *
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments){
   		foreach(self::$responseCodes as $code => $description){
   			// create the camel case version of the response
   			$camelCaseDescription = str_replace(' ', '', ucwords($description));
   			$camelCaseDescription[0] = strtolower($camelCaseDescription[0]);

   			if($name === $camelCaseDescription){
   				return self::create($code);
   			}
   		}

   		throw new \Exception("Invalid response: {$name}");
    }

	public static function isError(){
		return (self::$code >= 300 || self::$code < 200);
	}

	private static function setContentType($contentType){
		// set the content type
		self::$contentType = "application/json";
		header("Content-Type: " . self::$contentType);
	}

	public static function json($data){
		self::setContentType("application/json");
		return $data;
	} 
}
?>