<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception as Exception; // a common import

class allcoin extends okcoinusd {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'allcoin',
            'name' => 'Allcoin',
            'countries' => array ( 'CA' ),
            'has' => array (
                'CORS' => false,
            ),
            'extension' => '',
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/31561809-c316b37c-b061-11e7-8d5a-b547b4d730eb.jpg',
                'api' => array (
                    'web' => 'https://www.allcoin.com',
                    'public' => 'https://api.allcoin.com/api',
                    'private' => 'https://api.allcoin.com/api',
                ),
                'www' => 'https://www.allcoin.com',
                'doc' => 'https://www.allcoin.com/api_market/market',
                'referral' => 'https://www.allcoin.com',
            ),
            'status' => array (
                'status' => 'error',
                'updated' => null,
                'eta' => null,
                'url' => null,
            ),
            'api' => array (
                'web' => array (
                    'get' => array (
                        'Home/MarketOverViewDetail/',
                    ),
                ),
                'public' => array (
                    'get' => array (
                        'depth',
                        'kline',
                        'ticker',
                        'trades',
                    ),
                ),
                'private' => array (
                    'post' => array (
                        'batch_trade',
                        'cancel_order',
                        'order_history',
                        'order_info',
                        'orders_info',
                        'repayment',
                        'trade',
                        'trade_history',
                        'userinfo',
                    ),
                ),
            ),
        ));
    }

    public function fetch_markets ($params = array ()) {
        $result = array();
        $response = $this->webGetHomeMarketOverViewDetail ($params);
        $coins = $response['marketCoins'];
        for ($j = 0; $j < count ($coins); $j++) {
            $markets = $coins[$j]['Markets'];
            for ($k = 0; $k < count ($markets); $k++) {
                $market = $markets[$k]['Market'];
                $base = $this->safe_string($market, 'Primary');
                $quote = $this->safe_string($market, 'Secondary');
                $baseId = strtolower($base);
                $quoteId = strtolower($quote);
                $base = $this->safe_currency_code($base);
                $quote = $this->safe_currency_code($quote);
                $id = $baseId . '_' . $quoteId;
                $symbol = $base . '/' . $quote;
                $active = $market['TradeEnabled'] && $market['BuyEnabled'] && $market['SellEnabled'];
                $result[] = array (
                    'id' => $id,
                    'symbol' => $symbol,
                    'base' => $base,
                    'quote' => $quote,
                    'baseId' => $baseId,
                    'quoteId' => $quoteId,
                    'active' => $active,
                    'type' => 'spot',
                    'spot' => true,
                    'future' => false,
                    'maker' => $this->safe_float($market, 'AskFeeRate'), // BidFeeRate 0, AskFeeRate 0.002, we use just the AskFeeRate here
                    'taker' => $this->safe_float($market, 'AskFeeRate'), // BidFeeRate 0, AskFeeRate 0.002, we use just the AskFeeRate here
                    'precision' => array (
                        'amount' => $this->safe_integer($market, 'PrimaryDigits'),
                        'price' => $this->safe_integer($market, 'SecondaryDigits'),
                    ),
                    'limits' => array (
                        'amount' => array (
                            'min' => $this->safe_float($market, 'MinTradeAmount'),
                            'max' => $this->safe_float($market, 'MaxTradeAmount'),
                        ),
                        'price' => array (
                            'min' => $this->safe_float($market, 'MinOrderPrice'),
                            'max' => $this->safe_float($market, 'MaxOrderPrice'),
                        ),
                        'cost' => array (
                            'min' => null,
                            'max' => null,
                        ),
                    ),
                    'info' => $market,
                );
            }
        }
        return $result;
    }

    public function parse_order_status ($status) {
        $statuses = array (
            '-1' => 'canceled',
            '0' => 'open',
            '1' => 'open',
            '2' => 'closed',
            '10' => 'canceled',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function get_create_date_field () {
        // allcoin typo create_data instead of create_date
        return 'create_data';
    }

    public function get_orders_field () {
        // allcoin typo order instead of orders (expected based on their API docs)
        return 'order';
    }
}
