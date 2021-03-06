<?php

namespace Payment\Gateways\Providers\Soap;

use Payment\Gateways\Exceptions\InvalidArgumentException;
use Payment\Gateways\PaymentRequestNeeds;
use Payment\Gateways\PaymentRequestResponse;
use Payment\Gateways\Providers\BaseProvider;
use Payment\Gateways\VerifyResponse;
use SoapClient;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseSoapProvider extends BaseProvider implements SoapProviderInterface
{

    /**
     * Default options for soap.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = ['encoding' => 'utf-8'];

        if ($this->getConfig('proxy.enabled') === true) {
            $options['proxy_host'] = $this->getConfig('proxy.host');
            $options['proxy_port'] = $this->getConfig('proxy.port');

            if ($this->getConfig('proxy.use_credentials') === true) {
                $options['proxy_login'] = $this->getConfig('proxy.username');
                $options['proxy_password'] = $this->getConfig('proxy.password');
            }
        }


        return $options;
    }

    /**
     * Build soap client
     *
     * @param       $wsdl
     * @param array $options
     * @return \SoapClient
     */
    protected function buildSoap($wsdl, array $options = [])
    {
        return new SoapClient($wsdl, $options);
    }

    public function getClient()
    {
        return $this->buildSoap($this->getWsdl(), $this->getOptions());
    }
}