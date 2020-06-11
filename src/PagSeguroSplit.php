<?php

    namespace Aireset\PagSeguro;

    use Illuminate\Support\Arr;

    class PagSeguroSplit extends PagSeguroClient
    {
        /**
         * Informações do comprador.
         *
         * @var array
         */
        private $senderInfo = [];

        /**
         * Informações do portador do cartão de crédito.
         *
         * @var array
         */
        private $creditCardHolder = [];

        /**
         * Endereço do comprador.
         *
         * @var array
         */
        private $shippingAddress = [
            'shippingAddressRequired' => 'false',
        ];

        /**
         * Endereço de cobrança do comprador.
         *
         * @var array
         */
        private $billingAddress = [];

        /**
         * Itens da compra.
         *
         * @var array
         */
        private $items = [];
        private $itemsXml = [];

        private $receivers = [];

        /**
         * Número de Itens da compra.
         *
         * @var int
         */
        private $itemsCount = 0;

        /**
         * Valor adicional para a compra.
         *
         * @var float
         */
        private $extraAmount;

        /**
         * Identificador da compra.
         *
         * @var string
         */
        private $reference;

        /**
         * Frete.
         *
         * @var array
         */
        private $shippingInfo = [];

        /**
         * Extra config.
         *
         * @var array
         */
        private $config = [];

        /**
         * Define o tipo do comprador.
         *
         * @param string $senderType
         *
         * @return $this
         */
        public function setSenderType($senderType)
        {
            $this->senderType = $senderType;

            return $this;
        }

        /**
         * Define os dados do comprador.
         *
         * @param array $senderInfo
         *
         * @return $this
         */
        public function setSenderInfo(array $senderInfo)
        {
            $senderEmail = $this->sandbox ? 'teste@sandbox.pagseguro.com.br' : $this->sanitize($senderInfo, 'email');

            $senderPhone = $this->sanitizeNumber($senderInfo, 'phone');

            $array = [];
            $array['sender'] = [
                'name' => $this->sanitize($senderInfo, 'name'),
                'areaCode' => substr($senderPhone, 0, 2),
                'phone' => substr($senderPhone, 2),
                'email' => $senderEmail,
                'hash' => $this->checkValue($senderInfo, 'hash'),
                'CPF' => $this->sanitizeNumber($senderInfo, 'CPF'),
                'CNPJ' => $this->sanitizeNumber($senderInfo, 'CNPJ'),
                'cpf' => $this->sanitizeNumber($senderInfo, 'CPF'),
                'cnpj' => $this->sanitizeNumber($senderInfo, 'CNPJ'),
            ];

            $this->validateSenderInfo($array);
            $this->senderInfo = Arr::dot($array);

            return $this;
        }

        /**
         * Valida os dados contidos na array de informações do comprador.
         *
         * @param array $senderInfo
         */
        private function validateSenderInfo(array $senderInfo)
        {
            $rules = [
                'sender.name' => 'required|max:50',
                'sender.areaCode' => 'required|digits:2',
                'sender.phone' => 'required|digits_between:8,9',
                'sender.email' => 'required|email|max:60',
                'sender.hash' => 'required',
                'sender.cpf' => 'required_without:sender.cnpj|nullable|digits:11',
                'sender.cnpj' => 'required_without:sender.cpf|nullable|digits:14',
            ];
            $data = array_filter($senderInfo);

            $validator = $this->validator->make($data, $rules);

            if ($validator->fails()) {
                throw new PagSeguroException($validator->messages(), 1003);
            }
            // $this->validate($senderInfo, $rules);
        }

        /**
         * Define os dados do portador do cartão de crédito.
         *
         * @param array $creditCardHolder
         *
         * @return $this
         */
        public function setCreditCardHolder(array $creditCardHolder)
        {
            $cardHolderPhone = $this->sanitizeNumber($creditCardHolder, 'creditCard.HolderPhone');

            $creditCardHolder = [
                'creditCard.holder.name' => $this->fallbackValue($this->sanitize($creditCardHolder, 'creditCard.holder.name'), $this->senderInfo, 'sender.name'),
                'creditCard.holder.areaCode' => $this->fallbackValue(substr($cardHolderPhone, 0, 2), $this->senderInfo, 'sender.areaCode'),
                'creditCard.holder.phone' => $this->fallbackValue(substr($cardHolderPhone, 2), $this->senderInfo, 'sender.phone'),
                'creditCard.holder.CPF' => $this->fallbackValue($this->sanitizeNumber($creditCardHolder, 'creditCard.holder.CPF'), $this->senderInfo, 'sender.cpf'),
                'creditCard.holder.birthDate' => $this->sanitize($creditCardHolder, 'creditCard.holder.birthDate'),
            ];

            $this->validateCreditCardHolder($creditCardHolder);
            $this->creditCardHolder = $creditCardHolder;

            return $this;
        }

        /**
         * Valida os dados contidos na array de informações do portador do cartão de crédito.
         *
         * @param array $creditCardHolder
         */
        private function validateCreditCardHolder(array $creditCardHolder)
        {
            $rules = [
                'creditCard.holder.name' => 'required|max:50',
                'creditCard.holder.areaCode' => 'required|digits:2',
                'creditCard.holder.phone' => 'required|digits_between:8,9',
                'creditCard.holder.CPF' => 'required|digits:11',
                'creditCard.holder.birthDate' => 'required',
            ];

            $this->validate($creditCardHolder, $rules);
        }

        /**
         * Define o endereço do comprador.
         *
         * @param array $shippingAddress
         *
         * @return $this
         */
        public function setShippingAddress(array $shippingAddress)
        {
            $array = [];
            $array['shipping']['address'] = [
                'street' => $this->sanitize($shippingAddress, 'street'),
                'number' => $this->sanitize($shippingAddress, 'number'),
                'complement' => $this->sanitize($shippingAddress, 'complement'),
                'district' => $this->sanitize($shippingAddress, 'district'),
                'postalCode' => $this->sanitizeNumber($shippingAddress, 'postalCode'),
                'city' => $this->sanitize($shippingAddress, 'city'),
                'state' => strtoupper($this->checkValue($shippingAddress, 'state')),
                'country' => 'BRA',
            ];

            $this->validateShippingAddress($array);
            $this->shippingAddress = Arr::dot($array);

            return $this;
        }

        /**
         * Valida os dados contidos na array de endereço do comprador.
         *
         * @param array $shippingAddress
         */
        private function validateShippingAddress(array $shippingAddress)
        {
            if (isset($shippingAddress['shippingAddressRequired'])) {
                return;
            }

            $rules = [
                'shipping.address.street' => 'required|max:80',
                'shipping.address.number' => 'required|max:20',
                'shipping.address.complement' => 'max:40',
                'shipping.address.district' => 'required|max:60',
                'shipping.address.postalCode' => 'required|digits:8',
                'shipping.address.city' => 'required|min:2|max:60',
                'shipping.address.state' => 'required|min:2|max:2',
            ];

            $this->validate($shippingAddress, $rules);
        }

        /**
         * Define os itens da compra.
         *
         * @param array $items
         *
         * @return $this
         */
        public function setPrimaryReceiver($key)
        {
            // $this->config['primaryReceiver.publicKey'] = $key;

            $array = [];
            $array['primaryReceiver'] = [
                'publicKey' => $key
            ];

            $this->validatePrimaryReceiver($array);

            $this->config['primaryReceiver.publicKey'] = $key;
            return $this;
        }

        /**
         * Valida o recebedor primario
         *
         * @param array $items
         */
        private function validatePrimaryReceiver($item)
        {
            $rules = [
                'primaryReceiver.publicKey' => 'required',
            ];

            $this->validate($item, $rules);
        }

        /**
         * Define os itens da compra.
         *
         * @param array $items
         *
         * @return $this
         */
        public function setOthersReceivers(array $items)
        {
            $array = [];
            $receiver = [];
            foreach ($items as $key => $item) {
                $receiver['receiver'][$key]['publicKey'] = $this->sanitize($item, 'publicKey');
                $receiver['receiver'][$key]['split']['amount'] = $this->sanitizeMoney($item['split'], 'amount');

                $this->receivers[('receiver['. $key .'].publicKey')] = $this->sanitize($item, 'publicKey');
                $this->receivers[('receiver['. $key .'].split.amount')] = $this->sanitizeMoney($item['split'], 'amount');
            }

            $this->validateOthersReceivers($receiver);

            // Remonta o para o pagseguro
            // $array = Arr::dot($receiver);

            // $this->config = array_merge($this->config, $array);
            return $this;
        }

        /**
         * Valida os dados contidos na array de itens.
         *
         * @param array $items
         */
        private function validateOthersReceivers(array $items)
        {
            $rules = [
                'receiver.*.publicKey' => 'required',
                'receiver.*.split.amount' => 'required|numeric|between:0.00,9999999.00'
            ];

            $this->validate($items, $rules);
        }

        /**
         * Define os itens da compra.
         *
         * @param array $items
         *
         * @return $this
         */
        public function setItems(array $data)
        {
            $items = [];
            foreach ($data as $key => $item) {
                $items['item'][$key + 1]['id'] = $this->sanitize($item, 'id');
                $items['item'][$key + 1]['description'] = $this->sanitize($item, 'description');
                $items['item'][$key + 1]['amount'] = $this->sanitizeMoney($item, 'amount');
                $items['item'][$key + 1]['quantity'] = $this->sanitizeNumber($item, 'quantity');

                // $this->itemsXml[] = [
                    $this->itemsXml[('item['. ($key + 1) .'].id')] = $this->sanitize($item, 'id');
                    $this->itemsXml[('item['. ($key + 1) .'].description')] = $this->sanitize($item, 'description');
                    $this->itemsXml[('item['. ($key + 1) .'].amount')] = $this->sanitizeMoney($item, 'amount');
                    $this->itemsXml[('item['. ($key + 1) .'].quantity')] = $this->sanitizeNumber($item, 'quantity');
                // ];
            }

            $this->itemsCount = count($data);
            $this->validateItems($items);
            $this->items = Arr::dot($items);

            return $this;
        }

        /**
         * Valida os dados contidos na array de itens.
         *
         * @param array $items
         */
        private function validateItems($items)
        {
            $rules = [
                'item.*.id' => 'required|max:100',
                'item.*.description' => 'required|max:100',
                'item.*.amount' => 'required|numeric|between:0.00,9999999.00',
                'item.*.quantity' => 'required|integer|between:1,999',
            ];

            $this->validate($items, $rules);
        }

        /**
         * Define um valor adicional para a compra.
         *
         * @param float $extraAmount
         *
         * @return $this
         */
        public function setExtraAmount($extraAmount)
        {
            $this->extraAmount = $this->sanitizeMoney($extraAmount);

            return $this;
        }

        /**
         * Define um id de referência da compra no pagseguro.
         *
         * @param string $reference
         *
         * @return $this
         */
        public function setReference($reference)
        {
            $this->reference = $this->sanitize($reference);

            return $this;
        }

        /**
         * Define o valor e o tipo do frete cobrado.
         *
         * @param array $shippingInfo
         *
         * @return $this
         */
        public function setShippingInfo(array $shippingInfo)
        {
            $array = [];
            $array['shipping'] = [
                'type' => $this->sanitizeNumber($shippingInfo, 'type'),
                'cost' => $this->sanitizeMoney($shippingInfo, 'cost'),
            ];

            $this->validateShippingInfo($array);
            $this->shippingInfo = Arr::dot($array);

            return $this;
        }

        /**
         * Valida os dados contidos no array de frete.
         *
         * @param array $shippingInfo
         */
        private function validateShippingInfo(array $shippingInfo)
        {
            $rules = [
                'shipping.type' => 'required|integer|between:1,3',
                'shipping.cost' => 'required|numeric|between:0.00,9999999.00',
            ];

            $this->validate($shippingInfo, $rules);
        }

        /**
         * Envia a transação de checkout.
         *
         * @param array $paymentSettings
         *
         * @return mixed
         */
        public function send(array $paymentSettings)
        {
            if ($this->checkValue($paymentSettings, 'method') === 'creditCard.' && empty($this->billingAddress)) {
                $this->setBillingAddress([]);
            }

            $array = [];
            $array['payment'] = [
                'method' => $this->checkValue($paymentSettings, 'method'),
            ];
            if (isset($paymentSettings['bank'])) {
                $array['bank'] = [
                    'name' => $this->checkValue($paymentSettings['bank'], 'name'),
                ];
            }
            if (isset($paymentSettings['creditCard'])) {
                $array['creditCard'] = [
                    'token' => $this->checkValue($paymentSettings['creditCard'], 'token'),
                ];
            }
            if (isset($paymentSettings['installment'])) {
                $array['installment'] = [
                    'quantity' => $this->sanitizeNumber($paymentSettings['installment'], 'quantity'),
                    'value' => $this->sanitizeMoney($paymentSettings['installment'], 'value'),
                    'noInterestInstallmentQuantity' => $this->sanitizeNumber($paymentSettings['installment'], 'noInterestInstallmentQuantity'),
                ];
            }

            $this->validatePaymentSettings($array);

            $url = $this->url['transactionsSplit'];

            $config = [
                    // 'email' => $this->email,
                    // 'token' => $this->token,
                    'payment.mode' => 'default',
                    'currency' => 'BRL',
                    'reference' => $this->reference,
                    'extra.amount' => $this->extraAmount,
                    'notificationURL' => $this->notificationURL,
                ] + $this->config;

            // if (!empty($this->appId)) {
                // $config['appId'] = $this->appId;
                // $config['appKey'] = $this->appKey;
            // }


            if (empty($this->appId)) {
                throw new PagSeguroException('Obrigatório enviar o appId', 1003);
            }

            if (empty($this->appKey)) {
                throw new PagSeguroException('Obrigatório enviar o appKey', 1003);
            }

            $url .= "?appId=$this->appId&appKey=$this->appKey";

            $data = array_filter(
                array_merge(
                    $config,
                    Arr::dot($array),
                    $this->senderInfo,
                    $this->shippingAddress,
                    $this->creditCardHolder,
                    $this->billingAddress,
                    $this->shippingInfo,
                    $this->receivers,
                    $this->itemsXml
                )
            );

            return $this->sendTransaction(
                $data,
                $url,
                true,
                [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Accept: application/vnd.pagseguro.com.br.v3+xml'
                ]
            );
        }

        /**
         * Define o endereço do comprador.
         *
         * @param array $billingAddress
         *
         * @return $this
         */
        public function setBillingAddress(array $billingAddress)
        {
            $array = [];
            $array['billingAddress'] = [
                'street' => $this->fallbackValue($this->sanitize($billingAddress, 'street'), $this->shippingAddress, 'shipping.address.street'),
                'number' => $this->fallbackValue($this->sanitize($billingAddress, 'number'), $this->shippingAddress, 'shipping.address.number'),
                'complement' => $this->fallbackValue($this->sanitize($billingAddress, 'complement'), $this->shippingAddress, 'shipping.address.complement'),
                'district' => $this->fallbackValue($this->sanitize($billingAddress, 'district'), $this->shippingAddress, 'shipping.address.district'),
                'postalCode' => $this->fallbackValue($this->sanitizeNumber($billingAddress, 'postalCode'), $this->shippingAddress, 'shipping.address.postalCode'),
                'city' => $this->fallbackValue($this->sanitize($billingAddress, 'city'), $this->shippingAddress, 'shipping.address.city'),
                'state' => strtoupper($this->fallbackValue($this->checkValue($billingAddress, 'state'), $this->shippingAddress, 'shipping.address.state')),
                'country' => 'BRA',
            ];

            $this->validateBillingAddress($array);
            $this->billingAddress = Arr::dot($array);

            return $this;
        }

        /**
         * Valida os dados contidos na array de endereço do comprador.
         *
         * @param array $billingAddress
         */
        private function validateBillingAddress(array $billingAddress)
        {
            $rules = [
                'billingAddress.street' => 'required|max:80',
                'billingAddress.number' => 'required|max:20',
                'billingAddress.complement' => 'max:40',
                'billingAddress.district' => 'required|max:60',
                'billingAddress.postalCode' => 'required|digits:8',
                'billingAddress.city' => 'required|min:2|max:60',
                'billingAddress.state' => 'required|min:2|max:2',
            ];

            $this->validate($billingAddress, $rules);
        }

        /**
         * Valida os dados de pagamento.
         *
         * @param array $paymentSettings
         */
        private function validatePaymentSettings(array $paymentSettings)
        {
            $rules = [
                'payment.method' => 'required',
                'bank.name' => 'required_if:payment.method,eft',
                'creditCard.token' => 'required_if:payment.method,creditCard',
                'installment.quantity' => 'required_if:payment.method,creditCard|integer|between:1,18',
                'installment.value' => 'required_if:payment.method,creditCard|numeric|between:0.00,9999999.00',
                'installment.noInterestInstallmentQuantity' => 'integer|between:1,18',
            ];
            $data = array_filter($paymentSettings);

            $validator = $this->validator->make($data, $rules);

            if ($validator->fails()) {
                throw new PagSeguroException($validator->messages(), 1003);
            }

            // Valida o sender
            $senderInfo = [];
            foreach ($this->senderInfo as $key => $value) {
                Arr::set($senderInfo, $key, $value);
            }
            $this->validateSenderInfo($senderInfo);

            // Valida o Endereço de entrega
            $shippingAddress = [];
            foreach ($this->shippingAddress as $key => $value) {
                Arr::set($shippingAddress, $key, $value);
            }
            $this->validateShippingAddress($shippingAddress);

            // Valida os Items
            $shopItems = [];
            foreach ($this->items as $key => $value) {
                Arr::set($shopItems, $key, $value);
            }
            $this->validateItems($shopItems);

            if ($paymentSettings['payment']['method'] === 'creditCard.') {
                $creditCardHolder = [];
                foreach ($this->creditCardHolder as $key => $value) {
                    Arr::set($creditCardHolder, $key, $value);
                }
                $this->validateCreditCardHolder($creditCardHolder);

                $billingAddress = [];
                foreach ($this->billingAddress as $key => $value) {
                    Arr::set($billingAddress, $key, $value);
                }
                $this->validateBillingAddress($billingAddress);
            }

            if (!empty($this->shippingInfo)) {
                $shippingInfo = [];
                foreach ($this->shippingInfo as $key => $value) {
                    Arr::set($shippingInfo, $key, $value);
                }
                $this->validateShippingInfo($shippingInfo);
            }
        }

        /**
         * Cancela uma transação.
         *
         * @param string $transactionCode
         *
         * @return mixed
         */
        public function cancelTransaction($transactionCode)
        {
            return $this->sendTransaction([
                'email' => $this->email,
                'token' => $this->token,
                'transactionCode' => $transactionCode,
            ], $this->url['cancelTransaction']);
        }

        /**
         * Cancela uma transação.
         *
         * @param string $transactionCode
         *
         * @return mixed
         */
        public function aplicationAuthorization($providerCode, $redirectURL, $notificationURL, $permissions = null)
        {
            if (is_null($permissions)) {
                $permissions = "<code>CREATE_CHECKOUTS</code>
                <code>RECEIVE_TRANSACTION_NOTIFICATIONS</code>
                <code>SEARCH_TRANSACTIONS</code>
                <code>MANAGE_PAYMENT_PRE_APPROVALS</code>
                <code>DIRECT_PAYMENT</code>";
            }
            $parameters = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
            <authorizationRequest>
                <reference>PROV" . $providerCode . "</reference>
                <permissions>
                $permissions
                </permissions>
                <redirectURL>" . $redirectURL . "</redirectURL>
                <notificationURL>" . $notificationURL . "</notificationURL>
            </authorizationRequest>
            ";

            $url = $this->url['authorizationsRequest'];
            $url .= '?appId=' . env('PAGSEGURO_APP_ID') . '&appKey=' . env('PAGSEGURO_APP_KEY');

            $result = $this->executeCurl(
                $parameters,
                $url,
                ['Content-Type: application/xml; charset=UTF-8'],
                'POST'
            );

            return $this->formatResult($result);
        }
    }
