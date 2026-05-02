<?php

declare(strict_types=1);

return [

    'client' => [
        'navigation_label' => 'Clientes',
        'plural_model_label' => 'Clientes',
        'model_label' => 'Cliente',
        'table' => [
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
            'carrier' => 'Transportadora',
            'carrier_placeholder' => 'Nenhuma transportadora',
            'buyer_name' => 'Nome do comprador',
            'buyer_contact' => 'Contato do comprador',
            'buyer_placeholder' => '—',
        ],
        'infolist' => [
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
            'carrier' => 'Transportadora',
            'buyer_name' => 'Nome do comprador',
            'buyer_contact' => 'Contato do comprador',
        ],
        'form' => [
            'cpf_cnpj' => 'CPF/CNPJ',
            'corporate_name' => 'Razão Social',
            'fantasy_name' => 'Nome Fantasia',
            'email' => 'Email',
            'phone' => 'Telefone',
            'carrier' => 'Transportadora',
            'buyer_name' => 'Nome do comprador',
            'buyer_contact' => 'Contato do comprador',
        ],
    ],
    'sales_representative' => [
        'navigation_label' => 'Representantes',
        'plural_model_label' => 'Representantes',
        'model_label' => 'Representante',
        'table' => [
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
        ],
        'infolist' => [
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
        ],
        'form' => [
            'cpf' => 'CPF',
            'name' => 'Nome',
            'email' => 'Email',
            'password' => 'Senha',
        ],
    ],
    'product' => [
        'navigation_label' => 'Produtos',
        'plural_model_label' => 'Produtos',
        'model_label' => 'Produto',
        'table' => [
            'sku' => 'SKU',
            'barcode' => 'Código de Barras',
            'name' => 'Nome',
            'price' => 'Preço',
            'product_category_name' => 'Categoria do Produto',
        ],
        'infolist' => [
            'sku' => 'SKU',
            'barcode' => 'Código de Barras',
            'name' => 'Nome',
            'price' => 'Preço',
            'product_category_name' => 'Categoria do Produto',
        ],
        'form' => [
            'sku' => 'SKU',
            'barcode' => 'Código de Barras',
            'name' => 'Nome',
            'price' => 'Preço',
            'product_category_name' => 'Categoria do Produto',
        ],
    ],
    'payment_method' => [
        'navigation_label' => 'Métodos de Pagamento',
        'plural_model_label' => 'Métodos de Pagamento',
        'model_label' => 'Método de Pagamento',
        'table' => [
            'name' => 'Nome',
        ],
        'infolist' => [
            'name' => 'Nome',
        ],
        'form' => [
            'name' => 'Nome',
        ],
    ],
    'event' => [
        'navigation_label' => 'Eventos',
        'plural_model_label' => 'Eventos',
        'model_label' => 'Evento',
        'table' => [
            'name' => 'Nome',
            'start' => 'Início',
            'end' => 'Fim',
        ],
        'infolist' => [
            'name' => 'Nome',
            'start' => 'Início',
            'end' => 'Fim',
        ],
        'form' => [
            'name' => 'Nome',
            'start' => 'Início',
            'end' => 'Fim',
        ],
    ],
    'order' => [
        'navigation_label' => 'Pedidos',
        'plural_model_label' => 'Pedidos',
        'model_label' => 'Pedido',
        'table' => [
            'status' => 'Status',
            'event' => 'Evento',
            'client' => 'Cliente',
            'sales_representative' => 'Representante',
            'payment_method' => 'Método de Pagamento',
            'payment_method_placeholder' => 'Nenhum método',
        ],
        'infolist' => [
            'status' => 'Status',
            'event' => 'Evento',
            'client' => 'Cliente',
            'sales_representative' => 'Representante',
            'payment_method' => 'Método de Pagamento',
            'notes' => 'Observações',
        ],
        'form' => [
            'status' => 'Status',
            'event' => 'Evento',
            'client' => 'Cliente',
            'sales_representative' => 'Representante',
            'payment_method' => 'Método de Pagamento',
            'notes' => 'Observações',
            'buyer_name' => 'Nome do Comprador',
            'buyer_phone' => 'Telefone do Comprador',
        ],
        'actions' => [
            'cancel' => 'Cancelar',
            'complete' => 'Completar',
            'pending' => 'Pendente',
        ],
    ],
    'order_item' => [
        'navigation_label' => 'Itens do Pedido',
        'plural_model_label' => 'Itens do Pedido',
        'model_label' => 'Item do Pedido',
        'table' => [
            'order_id' => 'Pedido',
            'product_name' => 'Produto',
            'price' => 'Preço',
            'qty' => 'Quantidade',
            'total' => 'Total',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
            'summarize' => [
                'qty' => 'Quantidade',
                'total' => 'Total',
            ],
        ],
        'form' => [
            'product_name' => 'Produto',
            'price' => 'Preço',
            'qty' => 'Quantidade',
            'notes' => 'Observações',
        ],
        'infolist' => [
            'order_id' => 'Pedido',
            'product_name' => 'Produto',
            'price' => 'Preço',
            'qty' => 'Quantidade',
            'notes' => 'Observações',
        ],
    ],

];
