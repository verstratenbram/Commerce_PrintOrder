<?php

namespace RogueClarity\PrintOrder\Admin;

use modmore\Commerce\Admin\Page;

/**
 * PrintOrder for Commerce.
 *
 * Copyright 2018 by Tony Klapatch <tony.k@rogueclarity.com>
 *
 * This file is meant to be used with Commerce by modmore. A valid Commerce license is required.
 *
 * @package commerce_printorder
 * @license See core/components/commerce_printorder/docs/license.txt
 */
class PrintOrderPage extends Page
{
    public $key = 'printorder/print';
    public $title = 'commerce_printorder.print';

    public function setUp()
    {
        // Get the order from the requested order id
        $id = (int)$this->getOption('order');
        $order = $this->adapter->getObject('comOrder', [
            'id' => $id,
            'test' => $this->commerce->isTestMode()
        ]);

        if (!($order instanceof \comOrder)) {
            return $this->returnError($this->adapter->lexicon('commerce_printorder.not_found'));
        }

        // From the commerce.get_order snippet
        $data = [];
        // Load order and state
        $data['order'] = $order->toArray();
        $data['state'] = $order->getState();

        // Load items
        $items = [];
        foreach ($order->getItems() as $item) {
            $ta = $item->toArray();
            if ($product = $item->getProduct()) {
                $ta['product'] = $product->toArray();
            }
            $items[] = $ta;
        }
        $data['items'] = $items;

        // Load status
        $status = $order->getStatus();
        $data['status'] = $status->toArray();

        // Load transactions
        $trans = [];
        $transactions = $order->getTransactions();
        if ($transactions) {
            foreach ($transactions as $transaction) {
                if ($transaction->isCompleted()) {
                    $traa = $transaction->toArray();
                    if ($method = $transaction->getMethod()) {
                        $traa['method'] = $method->toArray();
                    }
                    $trans[] = $traa;
                }
            }
            $data['transactions'] = $trans;
        }

        // Load shipments
        $ships = [];
        $shipments = $order->getShipments();

        if ($shipments) {
            foreach ($shipments as $shipment) {
                $sta = $shipment->toArray();
                if ($method = $shipment->getShippingMethod()) {
                    $sta['method'] = $method->toArray();
                }
                $ships[] = $sta;
            }
            $data['shipments'] = $ships;
        }

        // Load addresses
        $ba = $order->getBillingAddress();
        if ($ba) {
            $data['billing_address'] = $ba->toArray();
        }

        $sa = $order->getShippingAddress();
        if ($sa) {
            $data['shipping_address'] = $sa->toArray();
        }

        $module = $this->adapter->getObject('comModule', ['name' => 'Print Order']);
        if ($module && $module->getProperty('system_settings')) {
            $settings = explode(',', $module->getProperty('system_settings'));
            if (count($settings) > 0) {
                foreach ($settings as $key) {
                    $data['config'][$key] = $this->adapter->getOption($key);
                }
            }
        }

        echo $this->commerce->twig->render('printorder/print.twig', $data);

        @session_write_close();
        exit();
    }
}
