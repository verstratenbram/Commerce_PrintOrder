# Commerce_PrintOrder

Adds a button to the dashboard in an order to print it with a customizable template. Similar to Packing Slip, but can print the entire order.

## Setup

Install the module from the modx.com provider and enable it in Commerce -> Configuration -> Modules. If needed, you can configure system settings to pass through to the template by entering a comma seperated list into the "Loaded system settings" field.

The print order will now show in the order actions. You can override the template by making a `printorder/print.twig` file in your template.

## Placeholders

These placeholders are available in the twig file:

- order
- state
- items
- transactions
- shipments
- billing_address
- shipping_address
- config (if any system settings are configured) 
- order_fields (since v1.2.0)
- taxes (since v1.2.0)

To see the full data of what these placeholders contain, see information on debugging templates here: https://docs.modmore.com/en/Commerce/v1/Front-end_Theming.html#page_Debugging+templates
