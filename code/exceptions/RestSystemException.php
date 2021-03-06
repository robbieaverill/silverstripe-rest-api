<?php

namespace Ntb\RestAPI;

/**
 * The system exception can be used for showing a system error like a missing file or a broken connection.
 * @author Christian Blank <c.blank@notthatbad.net>
 */
class RestSystemException extends \Exception {
    /*
     * @var int - override the default http status code if needed
     */
    protected $httpStatusCode;

    /**
     * RestSystemException constructor.
     * @param string $message the error message
     * @param int $errorCode the error code of the api
     * @param int $httpStatusCode the http status code in the response; Default=500
     */
    public function __construct($message, $errorCode, $httpStatusCode = 500) {
        parent::__construct($message, $errorCode);
        $this->httpStatusCode = $httpStatusCode;
    }


    /**
     * @return int
     */
    public function getHttpStatusCode() {
        return $this->httpStatusCode;
    }

    /**
     * @param int $httpStatusCode
     * @return $this
     */
    public function setHttpStatusCode($httpStatusCode) {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }

}
