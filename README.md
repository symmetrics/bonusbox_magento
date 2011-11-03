# Bonusbox's magento success page plugin

This documentation is meant for magento plugin developers who want to integrate the bonusbox functionality into a magento shop system.
If you have any questions or need any technical support, please contact Jan Riethmayer via [Mail](mailto:jan@bonusbox.me) or Skype: **riethmayer**.

## API Endpoint

**https://api.bonusbox.me/**

## API Version

When you want to call the API, you need to specify the Accept header like this :

    application/json,application/vnd.api;ver=1

Otherwise the resource is not accessible and will result in a `404`.

## Summary of Resource URL Patterns

**/success_pages**

## Errors

Bonusbox uses conventional HTTP response codes to indicate success or failure of an API request. In general, codes in the 2xx range indicate success, codes in the 4xx range indicate an error that resulted from the provided information (e.g. a required parameter was missing), and codes in the 5xx range indicate an error with bonusbox's servers.

All errors return JSON with a message describing the particular problem.

## Authentication

You authenticate to the bonusbox API by providing one of your API keys in the request. You can manage your API keys from your [account](#todo). You can have multiple API keys active at one time. Your API keys carry many privileges, so be sure to keep them secret!

Authentication to the API occurs via [HTTP Basic Auth](http://en.wikipedia.org/wiki/Basic_access_authentication). Provide your API key as the basic auth username. You do not need to provide a password.

All API requests must be made over [HTTPS](http://en.wikipedia.org/wiki/HTTP_Secure). Calls made over plain HTTP will fail. You must authenticate for all requests.

### Example Request

    curl https://api.bonusbox.me/ \
      -u F6PABG8JYBRpEMoPIDyzgB8iz0Z8Vho2:

### Test

* publishable: `pk_QscRNyat69EeAepXO02_VQARNhu93GpTlFhu-K`
* secret:      `kMBwsrKzdHS3OInejS6fvmPXW8Uz3Bg_embNF3SEcqI`

## HTTP Status Code Summary

**200** OK - Everything worked as expected.

**422** Bad Request - Often missing a required parameter.

**401** Unauthorized - No valid API key provided.

**402** Request Failed - Parameters were valid but request failed.

**404** Not Found - The requested item doesn't exist.

**500, 502, 503, 504** Server errors - something went wrong on bonusbox's end.

## Creating a new success page

We assume, visiting the success page means a payment has been triggered.
Therefore we grant `discounts` for the current customer if he does connect with bonusbox for his next shopping experience.

### Definition

    POST https://api.bonusbox.me/success_pages

### Example requests

    curl -H "Accept: application/json,application/vnd.api;ver=1" \
         -X POST http://localhost:3000/success_pages \
         -H "Content-Type: application/json" \
         -u pk_G2q9mfVNHHK0jxwUXGBY8y1mzibC4: \
         -d '{"items": [{ "sku":"123", "quantity":1, "code":"shipping", "price":"2000", "grand_total":"2380" }]}'

### Arguments

All arguments are passed via one JSON-Object. Supported arguments are:

* `addresses`          (optional)
 - data for location based recommendations.
* `bonusbox_user_text` (optional)
 - iFrame text displayed for a logged in bonusbox customer.
* `discounts`          (optional)
 - used to incentivize the customer with discount-codes
* `discounts_used`     (optional)
 - feedback which discounts have been used during this checkout.
* `items`              (**mandatory**)
 - customer's cart items (products, fees, ...)
* `new_user_text`      (optional)
 - iFrame text displayed for a user, not yet logged in to facebook (may be bonusbox_customer) or logged in facebook user without the bonusbox app installed.
* `order_number`       (optional)
 - for your tracking

One huge example (TODO: documentation for each value separately)

    { adresses: [
        { code : "billing",
          city : "Berlin",
          company : "Example GmbH",
          country: "Germany",
          email : "customer@example.com",
          first_name: "Bob",
          last_name : "Customer",
          phone : "111111111",
          street: "Mindener Strasse 20",
          zip: "10589" }, 
        { code : "shipping",
          city : "Berlin",
          country: "Germany",
          email : "customer@example.com",
          first_name: "Bob",
          last_name : "Customer",
          phone : "111111111",
          street: "Gipsstrasse 5",
          zip: "10117" }
      ],
      discount : {
        token       : "Winter 2011",
        expires_at  : "2012-02-28",
        title       : "Winterfest bei Shop",
      },
      discounts_used: ["Autumn 2011"],
      items : [
        { sku          : 1,
          price        : 2000,
          quantity     : 1,
          title        : "something nice",
          description  : "a description about nice",
          code         : "shipping",
          vat_rate     : "19" }, 
        { sku          : 1,
          price        : 2000,
          quantity     : 2,
          code         : "item",
          vat_rate     : "19",
          vat_amount   : 380,
          title        : "something nice",
          description  : "a description about nice",
          landing_page : "https://shop.example.com/item/2",
          image_url    : "http://assets.example.com/item/2.png" 
        }
      ],
        order_number : "shops_order_number",
        style_url    : "http://example.com/link_to_your_style.css"
        new_user_text : "Sichere dir %{{CREDITS:ACHIEVED}}% Bonusbox-Punkte und einen Gutschein fuer deinen naechsten Einkauf!\n\n%{{FACEBOOK:LOGIN}}%",
        bonusbox_user_text: "Du hast fuer deinen Einkauf %{{CREDITS:ACHIEVED}} Punkte bekommen. Die fehlen noch %{{CREDITS:LEVELUP}} um den naechsten %{{BADGE:LEVELUP}} zu erreichen.\n\n%{{BONUSBOX:APP:[$caption=Hier geht es zu deinen Bonusbox Rabatten]}}"
      }
    }

### Example Response
    
    {
      "success_page" : {
        "url"  : "https://api.bonusbox.me/8WrIpkogSj78OkKdBw0eqOtJMPg7woCCDv8JsA-51pI",
        "token" :"8WrIpkogSj78OkKdBw0eqOtJMPg7woCCDv8JsA-51pI"
      }
    }

### Example Error

    {
      "items": ["You have to add at least one item."]
    }

