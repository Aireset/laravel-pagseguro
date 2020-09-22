<?php
    
    namespace Aireset\PagSeguro;
    
    use Illuminate\Support\Facades\Log;
    
    class PagSeguroErrors
    {
        public static function errors($exception)
        {
            $explodeValue = explode(': ', $exception->message);
            
            if (isset($explodeValue[1])) {
                $explodeValue = $explodeValue[1];
            } else {
                $explodeValue = null;
            }
            
            if (isset($explodeValue[2])) {
                $explodeValue2 = $explodeValue[2];
            } else {
                $explodeValue2 = null;
            }
            
            switch ($exception->code) {
                case "5003":
                    $err = "Falha de comunicação com a instituição financeira";
                    break;
                case "10000":
                    $err = "Marca de cartão de crédito inválida";
                    break;
                case "10001":
                    $err = "Número do cartão de crédito com comprimento inválido";
                    break;
                case "10002":
                    $err = "Formato da data inválida";
                    break;
                case "10003":
                    $err = "Campo de segurança CVV inválido";
                    break;
                case "10004":
                    $err = "Código de verificação CVV é obrigatório";
                    break;
                case "10006":
                    $err = "Campo de segurança com comprimento inválido";
                    break;
                case "53004":
                    $err = "Quantidade inválida de itens";
                    break;
                case "53005":
                    $err = "É necessário informar a moeda";
                    break;
                case "53006":
                    $err = "Valor inválido para especificação da moeda: $explodeValue";
                    break;
                case "53007":
                    $err = "Referência inválida comprimento: $explodeValue";
                    break;
                case "53008":
                    $err = "URL de notificação inválida: $explodeValue";
                    break;
                case "53009":
                    $err = "URL de notificação com valor inválido: $explodeValue";
                    break;
                case "53010":
                    $err = "O e-mail do remetente é obrigatório";
                    break;
                case "53011":
                    $err = "Email do remetente com comprimento inválido: $explodeValue";
                    break;
                case "53012":
                    $err = "Email do remetente está com valor inválido: $explodeValue";
                    break;
                case "53013":
                    $err = "O nome do remetente é obrigatório";
                    break;
                case "53014":
                    $err = "Nome do remetente está com comprimento inválido: $explodeValue";
                    break;
                case "53015":
                    $err = "Nome do remetente está com valor inválido: $explodeValue";
                    break;
                case "53017":
                    $err = "Foi detectado algum erro nos dados do seu CPF: $explodeValue";
                    break;
                case "53018":
                    $err = "O código de área do remetente é obrigatório";
                    break;
                case "53019":
                    $err = "Há um conflito com o código de área informado, em relação a outros dados seus: $explodeValue";
                    break;
                case "53020":
                    $err = "É necessário um telefone do remetente";
                    break;
                case "53021":
                    $err = "Valor inválido do telefone do remetente: $explodeValue";
                    break;
                case "53022":
                    $err = "É necessário o código postal do endereço de entrega";
                    break;
                case "53023":
                    $err = "Código postal está com valor inválido: $explodeValue";
                    break;
                case "53024":
                    $err = "O endereço de entrega é obrigatório";
                    break;
                case "53025":
                    $err = "Endereço de entrega rua comprimento inválido: $explodeValue";
                    break;
                case "53026":
                    $err = "É necessário o número de endereço de entrega";
                    break;
                case "53027":
                    $err = "Número de endereço de remessa está com comprimento inválido: $explodeValue";
                    break;
                case "53028":
                    $err = "No endereço de entrega há um comprimento inválido: $explodeValue";
                    break;
                case "53029":
                    $err = "O endereço de entrega é obrigatório";
                    break;
                case "53030":
                    $err = "Endereço de entrega está com o distrito em comprimento inválido: $explodeValue";
                    break;
                case "53031":
                    $err = "É obrigatório descrever a cidade no endereço de entrega";
                    break;
                case "53032":
                    $err = "O endereço de envio está com um comprimento inválido da cidade: $explodeValue";
                    break;
                case "53033":
                    $err = "É necessário descrever o Estado, no endereço de remessa";
                    break;
                case "53034":
                    $err = "Endereço de envio está com valor inválido: $explodeValue";
                    break;
                case "53035":
                    $err = "O endereço do remetente é obrigatório";
                    break;
                case "53036":
                    $err = "O endereço de envio está com o país em um comprimento inválido: $explodeValue";
                    break;
                case "53037":
                    $err = "O token do cartão de crédito é necessário";
                    break;
                case "53038":
                    $err = "A quantidade da parcela é necessária";
                    break;
                case "53039":
                    $err = "Quantidade inválida no valor da parcela: $explodeValue";
                    break;
                case "53040":
                    $err = "O valor da parcela é obrigatório.";
                    break;
                case "53041":
                    $err = "Valor inválido de parcelamento: $explodeValue";
                    break;
                case "53042":
                    $err = "O nome do titular do cartão de crédito é obrigatório";
                    break;
                case "53043":
                    $err = "Nome do titular do cartão de crédito está com o comprimento inválido: $explodeValue";
                    break;
                case "53044":
                    $err = "O nome informado no formulário do cartão de Crédito precisa ser escrito exatamente da mesma forma que consta no seu cartão obedecendo inclusive, abreviaturas e grafia errada: $explodeValue";
                    break;
                case "53045":
                    $err = "O CPF do titular do cartão de crédito é obrigatório";
                    break;
                case "53046":
                    $err = "O CPF do titular do cartão de crédito está com valor inválido: $explodeValue";
                    break;
                case "53047":
                    $err = "A data de nascimento do titular do cartão de crédito é necessária";
                    break;
                case "53048":
                    $err = "TA data de nascimento do itular do cartão de crédito está com valor inválido: $explodeValue";
                    break;
                case "53049":
                    $err = "O código de área do titular do cartão de crédito é obrigatório";
                    break;
                case "53050":
                    $err = "Código de área de suporte do cartão de crédito está com valor inválido: $explodeValue";
                    break;
                case "53051":
                    $err = "O telefone do titular do cartão de crédito é obrigatório";
                    break;
                case "53052":
                    $err = "O número de Telefone do titular do cartão de crédito está com valor inválido: $explodeValue";
                    break;
                case "53053":
                    $err = "É necessário o código postal do endereço de cobrança";
                    break;
                case "53054":
                    $err = "O código postal do endereço de cobrança está com valor inválido: $explodeValue";
                    break;
                case "53055":
                    $err = "O endereço de cobrança é obrigatório";
                    break;
                case "53056":
                    $err = "A rua, no endereço de cobrança está com comprimento inválido: $explodeValue";
                    break;
                case "53057":
                    $err = "É necessário o número no endereço de cobrança";
                    break;
                case "53058":
                    $err = "Número de endereço de cobrança está com comprimento inválido: $explodeValue";
                    break;
                case "53059":
                    $err = "Endereço de cobrança complementar está com comprimento inválido: $explodeValue";
                    break;
                case "53060":
                    $err = "O endereço de cobrança é obrigatório";
                    break;
                case "53061":
                    $err = "O endereço de cobrança está com tamanho inválido: $explodeValue";
                    break;
                case "53062":
                    $err = "É necessário informar a cidade no endereço de cobrança";
                    break;
                case "53063":
                    $err = "O item Cidade, está com o comprimento inválido no endereço de cobrança: $explodeValue";
                    break;
                case "53064":
                    $err = "O estado, no endereço de cobrança é obrigatório";
                    break;
                case "53065":
                    $err = "No endereço de cobrança, o estado está com algum valor inválido: $explodeValue";
                    break;
                case "53066":
                    $err = "O endereço de cobrança do país é obrigatório";
                    break;
                case "53067":
                    $err = "No endereço de cobrança, o país está com um comprimento inválido: $explodeValue";
                    break;
                case "53068":
                    $err = "O email do destinatário está com tamanho inválido: $explodeValue";
                    break;
                case "53069":
                    $err = "Valor inválido do e-mail do destinatário: $explodeValue";
                    break;
                case "53070":
                    $err = "A identificação do item é necessária";
                    break;
                case "53071":
                    $err = "O ID do ítem está inválido: $explodeValue";
                    break;
                case "53072":
                    $err = "A descrição do item é necessária";
                    break;
                case "53073":
                    $err = "Descrição do item está com um comprimento inválido: $explodeValue";
                    break;
                case "53074":
                    $err = "É necessária quantidade do item";
                    break;
                case "53075":
                    $err = "Quantidade do item está irregular: $explodeValue";
                    break;
                case "53076":
                    $err = "Há um valor inválido na quantidade do item: $explodeValue";
                    break;
                case "53077":
                    $err = "O valor do item é necessário";
                    break;
                case "53078":
                    $err = "O Padrão do valor do item está inválido: $explodeValue";
                    break;
                case "53079":
                    $err = "Valor do item está irregular: $explodeValue";
                    break;
                case "53081":
                    $err = "O remetente está relacionado ao receptor! Esse é um erro comum que só o lojista pode cometer ao testar como compras. O erro surge quando uma compra é realizada com os mesmos dados cadastrados para receber os pagamentos da loja ou com um e-mail que é administrador da loja";
                    break;
                case "53084":
                    $err = "Receptor inválido! Esse erro decorre de quando o lojista usa dados relacionados com uma loja ou um conta do PagSeguro, como e-mail principal da loja ou o e-mail de acesso à sua conta não PagSeguro";
                    break;
                case "53085":
                    $err = "Método de pagamento indisponível";
                    break;
                case "53086":
                    $err = "A quantidade total do carrinho está inválida: $explodeValue";
                    break;
                case "53087":
                    $err = "Dados inválidos do cartão de crédito";
                    break;
                case "53091":
                    $err = "O Hash do remetente está inválido";
                    break;
                case "53092":
                    $err = "A Bandeira do cartão de crédito não é aceita";
                    break;
                case "53095":
                    $err = "Tipo de transporte está com padrão inválido: $explodeValue";
                    break;
                case "53096":
                    $err = "Padrão inválido no custo de transporte: $explodeValue";
                    break;
                case "53097":
                    $err = "Custo de envio irregular: $explodeValue";
                    break;
                case "53098":
                    $err = "O valor total do carrinho não pode ser negativo: $explodeValue";
                    break;
                case "53099":
                    $err = "Montante extra inválido: $explodeValue";
                    break;
                case "53101":
                    $err = "Valor inválido do modo de pagamento. O correto seria algo do tipo default e gateway";
                    break;
                case "53102":
                    $err = "Valor inválido do método de pagamento. O correto seria algo do tipo Credicard, Boleto, etc.";
                    break;
                case "53104":
                    $err = "O custo de envio foi fornecido, então o endereço de envio deve estar completo";
                    break;
                case "53105":
                    $err = "As informações do remetente foram fornecidas, portanto o e-mail também deve ser informado";
                    break;
                case "53106":
                    $err = "O titular do cartão de crédito está incompleto";
                    break;
                case "53109":
                    $err = "As informações do endereço de remessa foram fornecidas, portanto o e-mail do remetente também deve ser informado";
                    break;
                case "53110":
                    $err = "Banco EFT é obrigatório";
                    break;
                case "53111":
                    $err = "Banco EFT não é aceito";
                    break;
                case "53115":
                    $err = "Valor inválido da data de nascimento do remetente: $explodeValue";
                    break;
                case "53117":
                    $err = "Valor inválido do cnpj do remetente: $explodeValue";
                    break;
                case "53122":
                    $err = "O domínio do email do comprador está inválido. Você deve usar algo do tipo @sandbox.pagseguro.com.br: $explodeValue";
                    break;
                case "53140":
                    $err = "Quantidade de parcelas fora do limite. O valor deve ser maior que zero: $explodeValue";
                    break;
                case "53141":
                    $err = "Este remetente está bloqueado";
                    break;
                case "53142":
                    $err = "O cartão de crédito está com o token inválido";
                    break;
                
                case "10005":
                    $err = "As contas do vendedor e do comprador não podem estar relacionadas entre si.";
                    break;
                case "10009":
                    $err = "Método de pagamento atualmente indisponível.";
                    break;
                case "10020":
                    $err = "Método de pagamento inválido.";
                    break;
                case "10021":
                    $err = "Erro ao buscar dados do fornecedor do sistema.";
                    break;
                case "10023":
                    $err = "Método de pagamento indisponível.";
                    break;
                case "10024":
                    $err = "Comprador não registrado não é permitido.";
                    break;
                case "10025":
                    $err = "senderName não pode ficar em branco.";
                    break;
                case "10026":
                    $err = "senderEmail não pode ficar em branco.";
                    break;
                case "10049":
                    $err = "senderName obrigatório.";
                    break;
                case "10050":
                    $err = "senderEmail obrigatório.";
                    break;
                case "11002":
                    $err = "comprimento inválido do destinatário do e-mail: $explodeValue";
                    break;
                case "11006":
                    $err = "comprimento inválido de redirectURL: $explodeValue";
                    break;
                case "11007":
                    $err = "valor inválido de redirectURL: $explodeValue";
                    break;
                case "11008":
                    $err = "comprimento inválido de referência: $explodeValue";
                    break;
                case "11013":
                    $err = "valor inválido senderAreaCode: $explodeValue";
                    break;
                case "11014":
                    $err = "valor inválido do senderPhone: $explodeValue";
                    break;
                case "11027":
                    $err = "Quantidade do item fora do intervalo: $explodeValue";
                    break;
                case "11028":
                    $err = "O valor do item é obrigatório. (por exemplo, \"12,00\")";
                    break;
                case "11040":
                    $err = "Padrão inválido maxAge: $explodeValue. Deve ser um número inteiro.";
                    break;
                case "11041":
                    $err = "maxAge fora do intervalo: $explodeValue";
                    break;
                case "11042":
                    $err = "maxUses padrão inválido: $explodeValue. Deve ser um número inteiro.";
                    break;
                case "11043":
                    $err = "maxUses fora do intervalo: $explodeValue";
                    break;
                case "11054":
                    $err = "abandonURL/reviewURL comprimento inválido: $explodeValue";
                    break;
                case "11055":
                    $err = "abandonURL/reviewURL valor inválido: $explodeValue";
                    break;
                case "11071":
                    $err = "valor inválido preApprovalInitialDate.";
                    break;
                case "11072":
                    $err = "Valor inválido preApprovalFinalDate.";
                    break;
                case "11084":
                    $err = "o vendedor não tem opção de pagamento com cartão de crédito.";
                    break;
                case "11101":
                    $err = "Os dados de pré-aprovação são necessários.";
                    break;
                case "11163":
                    $err = "Você deve configurar uma URL de notificações de transações (Notificações de Transações) antes de usar este serviço.";
                    break;
                case "11211":
                    $err = "a pré-aprovação não pode ser paga duas vezes no mesmo dia.";
                    break;
                case "13005":
                    $err = "initialDate deve ser inferior ao limite permitido.";
                    break;
                case "13006":
                    $err = "initialDate não deve ter mais de 180 dias.";
                    break;
                case "13007":
                    $err = "initialDate deve ser menor ou igual a finalDate.";
                    break;
                case "13008":
                    $err = "o intervalo de pesquisa deve ser menor ou igual a 30 dias.";
                    break;
                case "13009":
                    $err = "finalDate deve ser inferior ao limite permitido.";
                    break;
                case "13010":
                    $err = "'formato inválido de initialDate use 'aaaa-MM-ddTHH:mm' (por exemplo, 2010-01-27T17:25).'";
                    break;
                case "13011":
                    $err = "'formato inválido finalDate use 'aaaa-MM-ddTHH:mm' (por exemplo, 2010-01-27T17:25). | 13013 | valor inválido da página.'";
                    break;
                
                case '13014':
                    $err = "valor inválido de maxPageResults (deve estar entre 1 e 1000).";
                    break;
                case '13017':
                    $err = "initialDate e finalDate são necessários na pesquisa por intervalo.";
                    break;
                case '13018':
                    $err = "o intervalo deve ser entre 1 e 30.";
                    break;
                case '13019':
                    $err = "intervalo de notificação é necessário.";
                    break;
                case '13020':
                    $err = "página é maior que o número total de páginas retornadas.";
                    break;
                case '13023':
                    $err = "Comprimento de referência mínimo inválido (1-255)";
                    break;
                case '13024':
                    $err = "Comprimento máximo de referência inválido (1-255)";
                    break;
                case '17008':
                    $err = "pré-aprovação não encontrada.";
                    break;
                case '17022':
                    $err = "status de pré-aprovação inválido para executar a operação solicitada. O status de pré-aprovação é $explodeValue.";
                    break;
                case '17023':
                    $err = "o vendedor não tem opção de pagamento com cartão de crédito.";
                    break;
                case '17024':
                    $err = "a pré-aprovação não é permitida para este vendedor $explodeValue";
                    break;
                case '17032':
                    $err = "destinatário inválido para check-out: $explodeValue verifique o status da conta do destinatário e se é uma conta do vendedor.";
                    break;
                case '17033':
                    $err = "preApproval.paymentMethod não é $explodeValue deve ser o mesmo da pré-aprovação.";
                    break;
                case '17035':
                    $err = "O formato dos dias de vencimento é inválido: $explodeValue.";
                    break;
                case '17036':
                    $err = "O valor dos dias de vencimento é inválido: $explodeValue. Qualquer valor de 1 a 120 é permitido.";
                    break;
                case '17037':
                    $err = "Os dias de vencimento devem ser menores que os dias de vencimento.";
                    break;
                case '17038':
                    $err = "O formato dos dias de expiração é inválido: $explodeValue.";
                    break;
                case '17039':
                    $err = "O valor de expiração é inválido: $explodeValue. Qualquer valor de 1 a 120 é permitido.";
                    break;
                case '17061':
                    $err = "Plano não encontrado.";
                    break;
                case '17063':
                    $err = "Hash é obrigatório.";
                    break;
                case '17065':
                    $err = "Documentos necessários.";
                    break;
                case '17066':
                    $err = "Quantidade de documentos inválida.";
                    break;
                case '17067':
                    $err = "O tipo de método de pagamento é obrigatório.";
                    break;
                case '17068':
                    $err = "O tipo de método de pagamento é inválido.";
                    break;
                case '17069':
                    $err = "O telefone é obrigatório.";
                    break;
                case '17070':
                    $err = "O endereço é obrigatório.";
                    break;
                case '17071':
                    $err = "O remetente é obrigatório.";
                    break;
                case '17072':
                    $err = "O método de pagamento é obrigatório.";
                    break;
                case '17073':
                    $err = "O cartão de crédito é obrigatório.";
                    break;
                
                case '17074':
                    $err = "O titular do cartão de crédito é obrigatório.";
                    break;
                case '17075':
                    $err = "O token do cartão de crédito é inválido.";
                    break;
                case '17078':
                    $err = "Data de expiração atingida.";
                    break;
                case '17079':
                    $err = "Limite de uso excedido.";
                    break;
                case '17080':
                    $err = "A pré-aprovação está suspensa.";
                    break;
                case '17081':
                    $err = "pedido de pagamento de pré-aprovação não encontrado.";
                    break;
                case '17082':
                    $err = "status de pedido de pagamento de pré-aprovação inválido para executar a operação solicitada. O status do pedido de pagamento de pré-aprovação é $explodeValue.";
                    break;
                case '17083':
                    $err = "A pré-aprovação já é $explodeValue.";
                    break;
                case '17093':
                    $err = "É necessário hash ou IP do remetente.";
                    break;
                case '17094':
                    $err = "Não pode haver novas assinaturas para um plano inativo.";
                    break;
                case '19001':
                    $err = "Valor inválido postalCode: $explodeValue";
                    break;
                case '19002':
                    $err = "addressStreet Comprimento inválido: $explodeValue";
                    break;
                case '19003':
                    $err = "addressNumber comprimento inválido: $explodeValue";
                    break;
                case '19004':
                    $err = "endereçoComplemento comprimento inválido: $explodeValue";
                    break;
                case '19005':
                    $err = "addressDistrict comprimento inválido: $explodeValue";
                    break;
                case '19006':
                    $err = "endereçoCidade comprimento inválido: $explodeValue";
                    break;
                case '19007':
                    $err = "addressState valor inválido: $explodeValue deve se ajustar ao padrão: \w $explodeValue2 (por exemplo, \"SP\")";
                    break;
                case '19008':
                    $err = "comprimento inválido addressCountry: $explodeValue";
                    break;
                case '19014':
                    $err = "valor inválido do senderPhone: $explodeValue";
                    break;
                case '19015':
                    $err = "addressCountry padrão inválido: $explodeValue";
                    break;
                case '50103':
                    $err = "o código postal não pode estar vazio";
                    break;
                case '50105':
                    $err = "o número do endereço não pode estar vazio";
                    break;
                case '50106':
                    $err = "distrito de endereço não pode estar vazio";
                    break;
                case '50107':
                    $err = "o país do endereço não pode estar vazio";
                    break;
                case '50108':
                    $err = "a cidade do endereço não pode estar vazia";
                    break;
                case '50131':
                    $err = "O endereço IP não segue um padrão válido";
                    break;
                case '50134':
                    $err = "a rua do endereço não pode estar vazia";
                    break;
                case '53151':
                    $err = "O valor do desconto não pode ficar em branco.";
                    break;
                case '53152':
                    $err = "Valor do desconto fora do intervalo. Para o tipo DISCOUNT_PERCENT o valor deve ser maior ou igual a 0,00 e menor ou igual a 100,00.";
                    break;
                case '53153':
                    $err = "não encontrado próximo pagamento para esta pré-aprovação.";
                    break;
                case '53154':
                    $err = "O status não pode ficar em branco.";
                    break;
                case '53155':
                    $err = "O tipo de desconto é obrigatório.";
                    break;
                case '53156':
                    $err = "Valor inválido do tipo de desconto. Os valores válidos são: DISCOUNT_AMOUNT e DISCOUNT_PERCENT.";
                    break;
                case '53157':
                    $err = "Valor do desconto fora do intervalo. Para o tipo DISCOUNT_AMOUNT o valor deve ser maior ou igual a 0,00 e menor ou igual ao valor máximo do pagamento correspondente.";
                    break;
                
                case'53158':
                    $err = "O valor do desconto é obrigatório.";
                    break;
                case'57038':
                    $err = "o estado do endereço é obrigatório.";
                    break;
                case'61007':
                    $err = "o tipo de documento é obrigatório.";
                    break;
                case'61008':
                    $err = "tipo de documento é inválido: $explodeValue";
                    break;
                case'61009':
                    $err = "o valor do documento é obrigatório.";
                    break;
                case'61010':
                    $err = "o valor do documento é inválido: $explodeValue";
                    break;
                case'61011':
                    $err = "cpf é inválido: $explodeValue";
                    break;
                case'61012':
                    $err = "cnpj é inválido: $explodeValue";
                    break;
                
                default:
                    $err = $exception->message;
            }
            
            Log::error($exception);
            Log::error($err);
            Log::error('Erro ao pagar');
            
            return $err;
        }
    }
