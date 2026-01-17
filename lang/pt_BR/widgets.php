<?php

declare(strict_types=1);

return [

    'stats_overview' => [
        'total_orders' => 'Total de Pedidos',
        'total_orders_description' => 'Pedidos realizados',
        'total_value' => 'Valor Total',
        'total_value_description' => 'Faturamento total',
        'average_ticket' => 'Ticket Médio',
        'average_ticket_description' => 'Valor médio por pedido',
        'pending_orders' => 'Pedidos Pendentes',
        'pending_orders_description' => 'Aguardando conclusão',
    ],

    'orders_by_status' => [
        'heading' => 'Pedidos por Status',
        'label' => 'Quantidade',
        'pending' => 'Pendente',
        'completed' => 'Concluído',
        'cancelled' => 'Cancelado',
    ],

    'sales_by_hour' => [
        'heading' => 'Vendas por Hora (Hoje)',
        'label' => 'Faturamento (R$)',
        'filters' => [
            'today' => 'Hoje',
            'all_time' => 'Todos os dias',
        ],
    ],

    'top_sales_representatives' => [
        'heading' => 'Top Representantes de Vendas',
        'position' => '#',
        'name' => 'Nome',
        'orders' => 'Pedidos',
        'total' => 'Total',
    ],

    'top_products' => [
        'heading' => 'Produtos Mais Vendidos',
        'position' => '#',
        'name' => 'Produto',
        'category' => 'Categoria',
        'quantity' => 'Quantidade',
        'total' => 'Total',
    ],

    'top_clients' => [
        'heading' => 'Top Clientes',
        'position' => '#',
        'name' => 'Nome',
        'document' => 'CPF/CNPJ',
        'orders' => 'Pedidos',
        'total' => 'Total',
    ],

    'payment_methods' => [
        'heading' => 'Formas de Pagamento',
        'label' => 'Valor (R$)',
    ],

    'sales_evolution' => [
        'heading' => 'Evolução de Vendas',
        'orders' => 'Pedidos',
        'value' => 'Faturamento (R$)',
        'filters' => [
            '7_days' => 'Últimos 7 dias',
            '15_days' => 'Últimos 15 dias',
            '30_days' => 'Últimos 30 dias',
        ],
    ],

];
