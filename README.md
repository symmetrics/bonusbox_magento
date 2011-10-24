# Bonusbox's magento success page plugin

This documentation is meant for magento plugin developers who want to integrate the bonusbox functionality into a magento shop system.
If you have any questions or need any technical support, please contact Jan Riethmayer via [Mail](mailto:jan@bonusbox.me) or Skype: **riethmayer**.

## API Keys

### Test

* publishable: pk_G2q9mfVNHHK0jxwUXGBY8y1mzibC4
* secret: F6PABG8JYBRpEMoPIDyzgB8iz0Z8Vho2
  
### Live

* publishable: pk_ifYd4fH3ld7WYkoPBpCMU94cVO63S
* secret: 2pHIRimRYgg9wgUeIUdDyoF0UxbT2iQK


## Creating a new achievement

We assume, visiting the success page means a payment has been triggered.
Therefore we grant `discounts` for the current customer if he does connect with bonusbox for his next shopping experience.

### Definition

    POST https://api.bonusbox.me/tokens

### Example request

    items : [{
      sku          : 1,
      price        : 2000,
      quantity     : 1,
      code         : "shipping",
      vat_rate     : "19%",
      vat_amount   : 380,
      total_price  : 2000,
      grand_total  : 2380
      }, {
      sku          : 1,
      price        : 2000,
      quantity     : 2,
      code         : "item",
      vat_rate     : "19%",
      vat_amount   : 380,
      total_price  : 4000,
      grand_total  : 4760,
      landing_page : "https://shop.example.com/item/2",
      image_url    : "http://assets.example.com/item/2.png"
      }],
    discounts_used: ["Autumn 2011"],
    discounts: [{
      token       : "Winter 2011",
      expires_at  : "2012-02-28",
      title       : "Winterfest bei Shop",
      description : "Alle Winterprodukte fuer 15% Rabatt",
    },{
      token       : "Winter 2011",
      expires_at  : "2012-02-28",
      title       : "Winterfest bei Shop",
      description : "Alle Winterprodukte fuer 15% Rabatt",
      app_id      : "SOME_OTHER_APPID_USING_BONUSBOX"
    }]
    
### Example Response


### Example Error    
