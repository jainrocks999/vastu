<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ 'plugins/franchise::withdrawal.invoice.title'|trans }} #{{ withdrawal.id }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
            }
            .header .logo {
                max-width: 200px;
                margin: 0 auto 10px auto;
            }
            .header p {
                margin: 0;
                font-size: 14px;
                color: #666666;
            }
            .invoice-details {
                margin-bottom: 20px;
            }
            .invoice-details h2 {
                margin: 0 0 10px 0;
                font-size: 18px;
            }
            .invoice-details p {
                margin: 0;
                font-size: 14px;
                color: #666666;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid #dddddd;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .total {
                text-align: right;
                font-size: 18px;
            }
            .notes {
                margin-top: 20px;
            }
            .notes p {
                margin: 0;
                font-size: 14px;
                color: #666666;
            }
            .stamp {
                border: 2px solid #555;
                color: #555;
                display: inline-block;
                font-size: 18px;
                left: 70%;
                line-height: 1;
                opacity: .5;
                padding: .3rem .75rem;
                position: fixed;
                text-transform: uppercase;
                top: 50%;
                transform: rotate(-14deg)
            }

            .is-failed {
                border-color: #d23;
                color: #d23
            }

            .is-completed {
                border-color: #0a9928;
                color: #0a9928
            }
        </style>
    </head>
    <body>
        <div class="header">
            {% if company.logo %}
                <img src="{{ company.logo }}" alt="{{ company.name }}" class="logo">
            {% else %}
                <h1>{{ company.name }}</h1>
            {% endif %}
            <p>{{ company.address }}</p>
            <p>{{ company.city }}, {{ company.state }} {{ company.zipcode }}</p>
            <p>{{ company.email }}</p>
            <p>{{ company.phone }}</p>
        </div>
        <div class="invoice-details">
            <h2>{{ 'plugins/franchise::withdrawal.invoice.title'|trans }} #{{ withdrawal.id }}</h2>
            <p>{{ 'plugins/franchise::withdrawal.invoice.created_at'|trans }}: {{ withdrawal.created_at }}</p>
            <p>{{ 'plugins/franchise::withdrawal.invoice.customer_name'|trans }}: {{ withdrawal.customer.name }}</p>
            <p>{{ 'plugins/franchise::withdrawal.invoice.payment_method'|trans }}: {{ withdrawal_payment_channel }}</p>
        </div>
        <table>
            <thead>
            <tr>
                <th>{{ 'plugins/franchise::withdrawal.invoice.earnings'|trans }}</th>
                <th>{{ 'plugins/franchise::withdrawal.invoice.fee'|trans }}</th>
                <th>{{ 'plugins/franchise::withdrawal.invoice.total'|trans }}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ withdrawal.amount|price_format }}</td>
                <td>{{ withdrawal.fee|price_format }} ({{ withdrawal_fee_percentage }}%)</td>
                <td>{{ (withdrawal.amount - withdrawal.fee)|price_format }}</td>
            </tr>
            </tbody>
        </table>
        <div class="total">
            <p>{{ 'plugins/franchise::withdrawal.invoice.total_due'|trans }}: {{ (withdrawal.amount - withdrawal.fee)|price_format }}</p>
            {% if (get_ecommerce_setting('enable_invoice_stamp', 1) == 1) %}
                {% if withdrawal.status == 'canceled' %}
                    <span class="stamp is-failed">
                {{ withdrawal.status }}
            </span>
                {% elseif (withdrawal_status) %}
                    <span class="stamp {% if withdrawal.status == 'completed' %} is-completed {% else %} is-failed {% endif %}">
                {{ withdrawal_status }}
            </span>
                {% endif %}
            {% endif %}
        </div>
        {% if withdrawal.description %}
            <div class="notes">
                <h2>{{ 'plugins/franchise::withdrawal.invoice.notes'|trans }}:</h2>
                <p>{{ withdrawal.description }}</p>
            </div>
        {% endif %}
        {% if withdrawal.bank_info %}
            <div class="notes">
                <h2>{{ 'plugins/franchise::withdrawal.invoice.payment_instructions'|trans }}:</h2>
                <p>{{ 'plugins/franchise::withdrawal.invoice.bank_name'|trans }}: {{ withdrawal.bank_info.name }}</p>
                <p>{{ 'plugins/franchise::withdrawal.invoice.bank_account_number'|trans }}: {{ withdrawal.bank_info.number }}</p>
                <p>{{ 'plugins/franchise::withdrawal.invoice.bank_account_name'|trans }}: {{ withdrawal.bank_info.full_name }}</p>
            </div>
        {% endif %}
    </body>
</html>
