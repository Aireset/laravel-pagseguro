<?php

    namespace Aireset\PagSeguro;

    class PagSeguroClient extends PagSeguroConfig
    {
        /**
         * Envia a transação JSON.
         *
         * @param array $parameters
         * @param string $url Padrão $this->url['transactions']
         * @param string $method
         * @param array $headers
         *
         * @return \SimpleXMLElement
         * @throws \Aireset\PagSeguro\PagSeguroException
         *
         */
        public function sendJsonTransaction(array $parameters, $url = null, $method = 'POST', array $headers = ['Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1', 'Content-Type: application/json; charset=UTF-8'])
        {
            if ($url === null) {
                $url = $this->url['transactions'];
            }
            $url .= '?email=' . $this->email . '&token=' . $this->token;

            $parameters = $this->array_filter_recursive($parameters);

            array_walk_recursive($parameters, function (&$value, $key) {
                $value = utf8_encode($value);
            });
            $parameters = json_encode($parameters);

            if ($method == 'GET') {
                $parameters = null;
            }

            $result = $this->executeCurl($parameters, $url, $headers, $method);

            return $this->formatResultJson($result);
        }

        /**
         * Aplica um array_filter recursivamente em um array.
         *
         * @param array $input
         *
         * @return array
         */
        public function array_filter_recursive(array $input)
        {
            foreach ($input as &$value) {
                if (is_array($value)) {
                    $value = $this->array_filter_recursive($value);
                }
            }

            return array_filter($input);
        }

        /**
         * Executa o Curl.
         *
         * @param array|string $parameters
         * @param string $url
         * @param array $headers
         * @param $method
         *
         * @return \SimpleXMLElement
         * @throws PagSeguroException
         *
         */
        public function executeCurl($parameters, $url, array $headers, $method)
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_ENCODING, "");
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

            if ($method == 'POST') {
                curl_setopt($curl, CURLOPT_POST, true);
            } elseif ($method == 'PUT') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            }

            if ($parameters !== null) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
            }

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !$this->sandbox);
            $result = curl_exec($curl);

            $getInfo = curl_getinfo($curl);
            if (isset($getInfo['http_code']) && $getInfo['http_code'] == '503') {
                $this->log->error('Serviço em manutenção.', ['Retorno:' => $result]);
                $message = PagSeguroErrors::errors((object)['message' => 'Serviço em manutenção.', 'code' => 1000]);
                throw new PagSeguroException($message, 1000);
            }
    
            if ($result === false) {
                $this->log->error('Erro ao enviar a transação', ['Retorno:' => $result]);
                $message = PagSeguroErrors::errors((object)['message' => curl_error($curl), 'code' => curl_errno($curl)]);
                throw new PagSeguroException($message, curl_errno($curl));
            }

            curl_close($curl);

            return $result;
        }

        /**
         * Formata o resultado e trata erros.
         *
         * @param array $result
         *
         * @return mixed
         * @throws \Aireset\PagSeguro\PagSeguroException
         *
         */
        public function formatResultJson($result)
        {
            if ($result === 'Unauthorized' || $result === 'Forbidden') {
                $this->log->error('Erro ao enviar a transação', ['Retorno:' => $result]);
    
                $message = PagSeguroErrors::errors((object)['message' => $result . ': Não foi possível estabelecer uma conexão com o PagSeguro.', 'code' => 1001]);
                throw new PagSeguroException($message, 1001);
            }
            if ($result === 'Not Found') {
                $this->log->error('Notificação/Transação não encontrada', ['Retorno:' => $result]);
    
                $message = PagSeguroErrors::errors((object)['message' => $result . ': Não foi possível encontrar a notificação/transação no PagSeguro.', 'code' => 1002]);
                throw new PagSeguroException($message, 1002);
            }

            $result = json_decode($result);

            if (isset($result->error) && $result->error === true) {
                $errors = $result->errors;

                $message = reset($errors);
                $code = key($errors);

                $this->log->error($message, ['Retorno:' => json_encode($result)]);
    
                $message = PagSeguroErrors::errors((object)['message' => $message, 'code' => (int)$code]);
                throw new PagSeguroException($message, (int)$code);
            }

            return $result;
        }

        /**
         * Inicia a Session do PagSeguro.
         *
         * @return string
         * @throws PagSeguroException
         *
         */
        public function startSessionApp()
        {
            return (string)$this->sendTransaction([
                'appId' => $this->appId,
                'appKey' => $this->appKey,
                'email' => $this->email,
                'token' => $this->token,
            ], $this->url['session'])->id;
        }

        /**
         * Envia a transação HTML.
         *
         * @param array $parameters
         * @param string $url Padrão $this->url['transactions']
         * @param bool $post
         * @param array $headers
         *
         * @return \SimpleXMLElement
         * @throws \Aireset\PagSeguro\PagSeguroException
         *
         */
        public function sendTransaction(array $parameters, $url = null, $post = true, array $headers = ['Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'])
        {
            if ($url === null) {
                $url = $this->url['transactions'];
            }

            $parameters = $this->array_filter_recursive($parameters);

            $data = '';
            foreach ($parameters as $key => $value) {
                $data .= $key . '=' . $value . '&';
            }
            $parameters = rtrim($data, '&');

            $method = 'POST';

            if (!$post) {
                $url .= '?' . $parameters;
                $parameters = null;
                $method = 'GET';
            }

            $result = $this->executeCurl($parameters, $url, $headers, $method);

            return $this->formatResult($result);
        }

        /**
         * Formata o resultado e trata erros.
         *
         * @param array $result
         *
         * @return \SimpleXMLElement
         * @throws \Aireset\PagSeguro\PagSeguroException
         *
         */
        public function formatResult($result)
        {
            if ($result === 'Unauthorized' || $result === 'Forbidden') {
                $this->log->error('Erro ao enviar a transação', ['Retorno:' => $result]);

                throw new PagSeguroException($result . ': Não foi possível estabelecer uma conexão com o PagSeguro.', 1001);
            }
    
            if ($result === 'Not Found') {
                $this->log->error('Notificação/Transação não encontrada', ['Retorno:' => $result]);

                throw new PagSeguroException($result . ': Não foi possível encontrar a notificação/transação no PagSeguro.', 1002);
            }

            $result = simplexml_load_string($result);

            if (isset($result->error) && isset($result->error->message)) {
                $this->log->error($result->error->message, ['Retorno:' => $result]);
                $message = PagSeguroErrors::errors((object)['message' => $result->error->message, 'code' => (int)$result->error->code]);
                throw new PagSeguroException($message, (int)$result->error->code);
            }

            return $result;
        }

        /**
         * Inicia a Session do PagSeguro.
         *
         * @return string
         * @throws PagSeguroException
         *
         */
        public function startSession()
        {
            return (string)$this->sendTransaction([
                'email' => $this->email,
                'token' => $this->token,
            ], $this->url['session'])->id;
        }

        /**
         * Retorna a transação da notificação.
         *
         * @param string $notificationCode
         * @param string $notificationType
         *
         * @return \SimpleXMLElement
         * @throws PagSeguroException
         *
         */
        public function notification($notificationCode, $notificationType = 'transaction')
        {
            if ($notificationType == 'transaction') {
                return $this->sendTransaction([
                    'email' => $this->email,
                    'token' => $this->token,
                ], $this->url['notifications'] . $notificationCode, false);
            } elseif ($notificationType == 'preApproval') {
                return $this->sendTransaction([
                    'email' => $this->email,
                    'token' => $this->token,
                ], $this->url['preApprovalNotifications'] . $notificationCode, false);
            } elseif ($notificationType == 'applicationAuthorization') {
                return $this->sendTransaction([
                    'appId' => $this->appId,
                    'appKey' => $this->appKey,
                ], $this->url['authorizationsNotification'] . $notificationCode, false);
            }
        }

        /**
         * Valida os dados.
         *
         * @param array $data
         * @param array $rules
         *
         * @throws \Aireset\PagSeguro\PagSeguroException
         */
        public function validate($data, $rules)
        {
            $data = array_filter($data);

            $validator = $this->validator->make($data, $rules);

            if ($validator->fails()) {
                $message = PagSeguroErrors::errors((object)['message' => $validator->messages()->first(), 'code' => 1003]);
                throw new PagSeguroException($message, 1003);
            }
        }

        /**
         * Limpa um valor deixando apenas números.
         *
         * @param mixed $value
         * @param string $key
         *
         * @return null|mixed
         */
        public function sanitizeNumber($value, $key = null)
        {
            return $this->sanitize($value, $key, '/\D/', '');
        }

        /**
         * Limpa um valor removendo espaços duplos.
         *
         * @param mixed $value
         * @param string $key
         * @param string $regex
         * @param string $replace
         *
         * @return null|mixed
         */
        public function sanitize($value, $key = null, $regex = '/\s+/', $replace = ' ')
        {
            $value = $this->checkValue($value, $key);

            return $value == null ? null : utf8_decode(trim(preg_replace($regex, $replace, $value)));
        }

        /**
         * Verifica a existência de um valor.
         *
         * @param mixed $value
         * @param string $key
         *
         * @return null|mixed
         */
        public function checkValue($value, $key = null)
        {
            if ($value != null) {
                if ($key !== null) {
                    return isset($value[$key]) ? $value[$key] : null;
                }

                return $value;
            }
        }

        /**
         * Limpa um valor deixando no formato de moeda.
         *
         * @param mixed $value
         * @param string $key
         *
         * @return null|number
         */
        public function sanitizeMoney($value, $key = null)
        {
            $value = $this->checkValue($value, $key);

            return $value == null ? $value : number_format($value, 2, '.', '');
        }

        /**
         * Verifica a existência de um valor, e utiliza outro caso necessário.
         *
         * @param mixed $value
         * @param mixed $fValue
         * @param string $fKey
         *
         * @return null|mixed
         */
        public function fallbackValue($value, $fValue, $fKey)
        {
            return $value != null ? $value : $this->checkValue($fValue, $fKey);
        }
    }
