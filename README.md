# Bonusbox's magento success page plugin

This documentation is meant for magento plugin developers who want to integrate the bonusbox functionality into a magento shop system.
If you have any questions or need any technical support, please contact Jan Riethmayer via [Mail](mailto:jan@bonusbox.me) or Skype: **riethmayer**.

## API Endpoint

**https://api.bonusbox.me/**

## Summary of Resource URL Patterns

**/tokens**

## Errors

* Invalid Request Errors

Type: `invalid_request_error`

* API Errors

Type: `api_error`

bonusbox uses conventional HTTP response codes to indicate success or failure of an API request. In general, codes in the 2xx range indicate success, codes in the 4xx range indicate an error that resulted from the provided information (e.g. a required parameter was missing), and codes in the 5xx range indicate an error with bonusbox's servers.

All errors return JSON with a type (one of `invalid_request_error` or `api_error`) and message describing the particular problem.

## Authentication

You authenticate to the bonusbox API by providing one of your API keys in the request. You can manage your API keys from your [account](#todo). You can have multiple API keys active at one time. Your API keys carry many privileges, so be sure to keep them secret!

Authentication to the API occurs via [HTTP Basic Auth](http://en.wikipedia.org/wiki/Basic_access_authentication). Provide your API key as the basic auth username. You do not need to provide a password.

All API requests must be made over [HTTPS](http://en.wikipedia.org/wiki/HTTP_Secure). Calls made over plain HTTP will fail. You must authenticate for all requests.

### Example Request

    curl https://api.bonusbox.me/ \
      -u F6PABG8JYBRpEMoPIDyzgB8iz0Z8Vho2:

### Test

* publishable: pk_G2q9mfVNHHK0jxwUXGBY8y1mzibC4
* secret: F6PABG8JYBRpEMoPIDyzgB8iz0Z8Vho2
  
### Live

* publishable: pk_ifYd4fH3ld7WYkoPBpCMU94cVO63S
* secret: 2pHIRimRYgg9wgUeIUdDyoF0UxbT2iQK

## HTTP Status Code Summary

**200** OK - Everything worked as expected.

**400** Bad Request - Often missing a required parameter.

**401** Unauthorized - No valid API key provided.

**402** Request Failed - Parameters were valid but request failed.

**404** Not Found - The requested item doesn't exist.

**500, 502, 503, 504** Server errors - something went wrong on bonusbox's end.

## Creating a new achievement

We assume, visiting the success page means a payment has been triggered.
Therefore we grant `discounts` for the current customer if he does connect with bonusbox for his next shopping experience.

### Definition

    POST https://api.bonusbox.me/tokens

### Example request

    adresses: [
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
    discounts: [
    { token       : "Winter 2011",
      expires_at  : "2012-02-28",
      title       : "Winterfest bei Shop",
      description : "Alle Winterprodukte fuer 15% Rabatt" }, 
    { token       : "Winter 2011 bei Alzando",
      expires_at  : "2012-02-28",
      title       : "Winterfest bei Alzando,
      description : "Alle Winterprodukte fuer 15% Rabatt bei Alzando",
      app_id      : "SOME_OTHER_APPID_USING_BONUSBOX" }
    ],
    discounts_used: ["Autumn 2011"],
    items : [
      { sku          : 1,
        price        : 2000,
        quantity     : 1,
        code         : "shipping",
        vat_rate     : "19%",
        vat_amount   : 380,
        total_price  : 2000,
        grand_total  : 2380 }, 
      { sku          : 1,
        price        : 2000,
        quantity     : 2,
        code         : "item",
        vat_rate     : "19%",
        vat_amount   : 380,
        total_price  : 4000,
        grand_total  : 4760,
        landing_page : "https://shop.example.com/item/2",
        image_url    : "http://assets.example.com/item/2.png" 
      }
    ],
    order_id : "shops_order_number",
    
    }
    
### Example Response


### Example Error

