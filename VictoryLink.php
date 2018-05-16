<?php

/**
 * Class VictoryLink
 * Wrapper class for sending SMS using Victory Link SOAP API
 * @package Modules\Delivery\Clients
 */
class VictoryLink
{

    /**
     * store wsdl Url to Call
     *
     * @var string
     */
    private $wsdlUrl;

    /**
     * Username provided by victory link
     *
     * @var string
     */
    private $username;

    /**
     * Password provided by victory link
     *
     * @var string
     */
    private $password;


    /**
     * The message language
     * “E” for English
     * “A” for Arabic.
     * @var string
     */
    private $SMSLang;

    /**
     * VictoryLink constructor.
     * @description return base url when take obj from class Bsms
     * @author Nedal Elghamry <nedal.elghamry@e7gezly.com>
     * @param string $url
     * @param string $account_id
     * @param string $user_name
     * @param string $password
     * @access public
     */
    public function __construct($user_name, $password, $url)
    {
        $this->wsdlUrl = $url;
        $this->username = $user_name;
        $this->password = $password;
        $this->SMSLang = 'E';
        $this->SMSSender = 'E7gezly';
    }

    /**
     * @description Used to send single SMS to single mobile number.
     * @param $sms_body
     * @param $mobile
     * @return mixed
     * This section is intended to describe the return values for the web method and their meanings;
     * the web method returns an XML document as below containing an integer that represents the result:
     *  0 (User is not subscribed)
     * -1 (User is not subscribed)
     * -5 (out of credit.)
     * -10 (Queued Message, no need to send it again.)
     * -11 (Invalid language.)
     * -12 (SMS is empty.)
     * -13 (Invalid fake sender exceeded 12 chars or empty.)
     * -25 (Sending rate greater than receiving rate (only for send/receive accounts).)
     * -100 (other error)
     */
    public function sendSMS($sms_body, $mobile)
    {
        $client = new SoapClient($this->wsdlUrl, array('cache_wsdl' => WSDL_CACHE_BOTH));

        $result = $client->SendSMS(array(
            "UserName" => $this->username,
            "Password" => $this->password,
            "SMSLang" => $this->SMSLang,
            "SMSSender" => $this->SMSSender,
            "SMSText" => $sms_body,
            "SMSReceiver" => $mobile
        ));

        return $result;
    }

    /**
     * @description It is used to check the account available credit in case of limited accounts. It returns the number
     * of SMSs available to be sent.
     * @param Username
     * @param Password
     * @return mixed
     * returns an integer that represents the number of SMSs available to be sent. It returns 0 in case
     * of unlimited accounts.
     */
    public function checkCredit(){
        $client = new SoapClient($this->wsdlUrl, array('cache_wsdl' => WSDL_CACHE_BOTH));

        $result = $client->CheckCredit(array(
            "username" => $this->username,
            "password" => $this->password
        ));

        return $result->CheckCreditResult;
    }

}
