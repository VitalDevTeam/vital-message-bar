# Message Bar
A simple WordPress plugin for adding a message bar to the site.

## Requirements
* Advanced Custom Fields Pro
* WordPress > 5.2
* Theme must support [wp_body_open](https://developer.wordpress.org/reference/functions/wp_body_open/) hook

## WP Engine Warning
You must contact WP Engine support and ask them to exclude the `vtlmb_dismissed` cookie from being cached. They must do this on EACH of the site environments (prod, dev, and staging). This will prevent the server from caching the message bar which causes it to appear even after it's been dismissed. 

## Hooks

### vtlmb_enqueue_css
Disables default CSS stylesheet. Does not disable colors applied through the plugin settings page. Clear color picker fields to disable those colors.

#### Parameters
**$enqueue_css** (bool) Whether the default CSS file should be enqueued. Default true.

#### Example
`add_filter('vtlmb_enqueue_css', '__return_false');`

### vtlmb_message_bar
Customize HTML output.

#### Parameters
**$output** (string) The HTML output of the entire message bar.

**$message** (string) The message text.

**$link** (array) The message link data.

### vtlmb_dismiss_icon
Customize dismiss button icon. Use an inline SVG to maintain style link to color picker fields on plugin settings page.

#### Parameters
**$dismiss_icon** (string) The HTML of the dismiss icon.

## Actions

### vtlmb_bar
The message bar will use the `wp_body_open` hook by default. If you want to better control where the bar markup is placed, remove `wp_body_open` and use `vtlmb_bar` in your tempalte.

#### Example
`do_action('vtlmb_bar');`
