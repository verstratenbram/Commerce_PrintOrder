# Commerce_PrintOrder

Adds a button to the dashboard in an order to print it with a customizable template. Similar to Packing Slip, but can print the entire order.

## Setup

Install the module from the modx.com provider and enable it in Commerce -> Configuration -> Modules. On 0.11.1 and below, modify `admin/widgets/action-button.twig` in your template to include `{% if action.newWindow %} target="_blank"{% endif %}`. This allows it to open the print view in a new tab. Example: 

```HTML
<a href="{{ action.url }}" class="ui button {% if action.modal %}commerce-ajax-modal{% endif %}"{% if action.newWindow %} target="_blank"{% endif %}>
    {% if action.icon %}<i class="icon {{ action.icon }}"></i>{% endif %}
    {{ action.title }}
</a>
```

The print order will now show in the order actions. You can override the template by making a `printorder/print.twig` file in your template.
