<?php
namespace App\PaymentGateway;
use App\PaymentGateway\Aop\AopClient;
use App\PaymentGateway\Aop\SignData;
use App\PaymentGateway\Aop\request\AlipayTradeCreateRequest;
use App\PaymentGateway\Aop\request\AlipayTradeQueryRequest;
class AliPay {
    protected  $aop;
    public function __construct() {
        $this->aop = new AopClient ();
        $this->aop->gatewayUrl            = config('alipay.gatewayUrl');
        $this->aop->appId                 = config('alipay.appId');
        $this->aop->rsaPrivateKey         = config('alipay.rsaPrivateKey');
        $this->aop->alipayrsaPublicKey    = config('alipay.alipayrsaPublicKey');        
        $this->aop->apiVersion            = config('alipay.apiVersion');
        $this->aop->signType              = config('alipay.signType');
        $this->aop->postCharset           = config('alipay.postCharset');
        $this->aop->format                = config('alipay.format');
        
    }
    public function createAlipayOrder($paymentData)
    {
        $response = array();
        $objRequest = new AlipayTradeCreateRequest ();
        $objRequest->setBizContent(json_encode($paymentData));
        $processRequest = $this->aop->pageExecute ( $objRequest,'GET'); 
        $response = json_decode(file_get_contents($processRequest));
        return $response;              
        
    }
    public function checkAlipayPaymentStatus($paymentData){
             //$paymentData['trade_no'] =$result->alipay_trade_create_response->trade_no;
             $r = new AlipayTradeQueryRequest();
             $r->setBizContent(json_encode($paymentData));
             $r = $this->aop->execute ($r);
             return $r;        
    }
    
}
