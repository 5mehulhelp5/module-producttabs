# Panth Product Tabs - User Guide

Panth_ProductTabs customizes the product detail page tabs in Magento 2.
It supports horizontal and vertical layouts, multiple animation types,
mobile accordion, custom CMS block and attribute tabs, and works with
both Hyva and Luma themes.

---

## Table of contents

1. [Installation](#1-installation)
2. [Verifying the module is active](#2-verifying-the-module-is-active)
3. [General settings](#3-general-settings)
4. [Design settings](#4-design-settings)
5. [Tab labels](#5-tab-labels)
6. [Tab visibility](#6-tab-visibility)
7. [Tab sort order](#7-tab-sort-order)
8. [Custom CMS block tabs](#8-custom-cms-block-tabs)
9. [Custom attribute tabs](#9-custom-attribute-tabs)
10. [Widget usage](#10-widget-usage)
11. [Deep linking](#11-deep-linking)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. Installation

### Composer (recommended)

```bash
composer require mage2kishan/module-producttabs
bin/magento module:enable Panth_ProductTabs
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual zip

1. Download the extension package zip
2. Extract to `app/code/Panth/ProductTabs`
3. Run the same `module:enable ... cache:flush` commands above

---

## 2. Verifying the module is active

```bash
bin/magento module:status Panth_ProductTabs
# Module is enabled
```

Navigate to any product detail page to see the customized tabs.

---

## 3. General settings

Open **Stores > Configuration > Panth Extensions > Product Tabs >
General Settings**.

| Setting | Default | What it does |
|---|---|---|
| **Enable Module** | Yes | Master toggle for the entire module. When disabled, Magento's default tabs render. |
| **Sticky Tab Navigation** | No | Makes the tab nav bar stick to the top of the viewport when you scroll past it. |
| **Lazy Load Reviews** | No | Defers the Reviews tab JavaScript initialization until the user clicks the Reviews tab. Improves initial page load time. |
| **Default Open Tab** | First Available Tab | Choose which tab opens by default: First Available, Description, More Information, or Reviews. |

---

## 4. Design settings

Open **Stores > Configuration > Panth Extensions > Product Tabs >
Design Settings**.

| Setting | Default | What it does |
|---|---|---|
| **Tab Style** | Horizontal | Choose between horizontal (tabs across the top) or vertical (tabs down the left side). |
| **Animation Type** | Fade | Animation when switching tabs: Fade, Slide, or None. |
| **Mobile Behavior** | Accordion | On mobile devices, choose Accordion (stacked collapsible panels) or Horizontal Scroll (swipeable tab strip). |
| **Open First Tab by Default** | Yes | Automatically expand the first tab when the page loads. |
| **Accordion on Mobile** | Yes | Convert tabs to accordion panels on screens narrower than 768px. |

---

## 5. Tab labels

Open **Stores > Configuration > Panth Extensions > Product Tabs >
Tab Labels**.

Override the default labels for the three built-in tabs:
- **Description Tab Label** (default: "Description")
- **More Information Tab Label** (default: "More Information")
- **Reviews Tab Label** (default: "Reviews")

The Reviews label automatically appends the approved review count in
parentheses, e.g. "Reviews (12)".

---

## 6. Tab visibility

Open **Stores > Configuration > Panth Extensions > Product Tabs >
Tab Visibility**.

Toggle each built-in tab on or off:
- Show Description Tab (default: Yes)
- Show More Information Tab (default: Yes)
- Show Reviews Tab (default: Yes)

---

## 7. Tab sort order

Open **Stores > Configuration > Panth Extensions > Product Tabs >
Tab Sort Order**.

Set a numeric sort order for each tab. Lower numbers appear first.

| Tab | Default |
|---|---|
| Description | 10 |
| More Information | 20 |
| Reviews | 30 |

Custom CMS and attribute tabs also have their own sort order field.

---

## 8. Custom CMS block tabs

Open **Stores > Configuration > Panth Extensions > Product Tabs >
Custom CMS Block Tabs**.

Click "Add CMS Block Tab" to add a new row. Each row has:

| Field | Description |
|---|---|
| **Enabled** | Enter `1` to enable, `0` to disable |
| **Tab Title** | The label shown on the tab button |
| **CMS Block Identifier** | The identifier of a CMS Static Block (e.g. `shipping-info`) |
| **Icon Class** | Optional Bootstrap Icon class (e.g. `bi-truck`) |
| **Sort Order** | Numeric sort order among all tabs |

The CMS block content renders inside the tab panel. If the block is
empty or does not exist, the tab is automatically hidden.

---

## 9. Custom attribute tabs

Open **Stores > Configuration > Panth Extensions > Product Tabs >
Custom Attribute Tabs**.

Click "Add Attribute Tab" to add a new row. Each row has:

| Field | Description |
|---|---|
| **Enabled** | Enter `1` to enable, `0` to disable |
| **Tab Title** | The label shown on the tab button |
| **Attribute Codes** | Comma-separated product attribute codes (e.g. `material,color,weight`) |
| **Icon Class** | Optional Bootstrap Icon class (e.g. `bi-gear`) |
| **Sort Order** | Numeric sort order among all tabs |

Attributes with empty, null, "No", or "N/A" values are automatically
excluded. If no attributes have valid values for a given product, the
tab is hidden.

---

## 10. Widget usage

You can also insert Product Tabs as a widget in CMS pages or blocks:

1. Go to **Content > Pages** or **Content > Blocks**
2. Open the editor and click "Insert Widget"
3. Select **Panth Product Tabs**
4. Configure Tab Style and Animation Type
5. Optional: enter a "Product ID for Reviews" to show the approved reviews of that product (the widget has no product tabs to render outside the product page, so without it nothing is shown there)
6. Save

---

## 11. Deep linking

The module supports URL hash-based deep linking. Append a hash to any
product URL to open a specific tab on page load:

- `https://yourstore.com/product.html#description`
- `https://yourstore.com/product.html#more-information`
- `https://yourstore.com/product.html#reviews`

Custom tab aliases also work (e.g. `#cms_shipping-info`).

---

## 12. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| Default Magento tabs still showing | Module disabled or cache not flushed | Check `bin/magento module:status Panth_ProductTabs` and flush cache |
| Tabs not switching on click | JS error on page | Check browser console for errors; run `bin/magento setup:static-content:deploy -f` |
| Custom CMS tab not appearing | CMS block identifier incorrect or block is empty | Verify the block exists and has content in Content > Blocks |
| Attribute tab not appearing | All specified attributes are empty for the product | Ensure the product has values for at least one of the configured attributes |
| Accordion not showing on mobile | Mobile behavior set to "Horizontal Scroll" | Change Mobile Behavior to "Accordion" in Design Settings |
| Hyva template not rendering | Theme detection issue | Ensure Panth_Core is installed and `isHyva()` detects your theme correctly |

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422

Free email support is provided on a best-effort basis.
