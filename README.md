# Magento 2 Free Gifts GraphQL / PWA (FREE)

[Mageplaza Free Gifts for Magento 2](https://www.mageplaza.com/magento-2-free-gifts/) is a handy tool that simplifies the process of giving away free gifts to customers on the online store. 

The biggest advantage of offering free gifts is to improve customer experience and boost sales. Depending on the store owners' purposes or strategies, they can provide customers with free gift when they purchase a specific product. Usually, free gifts can be used to gather customers' attention to a new product or a sale program. Free gifts are an incentive that stimulates customers' purchasing decisions and builds their retention. 

The free gifts are designed to display on the Product page and Shopping Cart page, which is good for store owners to avoid customers from abandoning their carts right before the checkout. In case the free gifts are offered as discount coupons, customers can get the gifts and apply them to their order right away. The extension also enables customers to freely select free gifts and change the gift they choose from the list to get the most like ones. 

The store admin can set the rules to reward gifts to customers based on product attributes and cart attributes. For example, if the cart's subtotal is more than $100, they will be given free gifts. 

Another method is setting the conditions based on the order value of customers to apply the free gifts. In other words, it's determining the prices of gifts, which can be set in three ways: free, discount by percent, and fixed prices. For example, if one's order reaches $1000, he will get one more item free or 20% off for two items. Free shipping is another appealing gift that many customers look for when shopping online, and you can add this offer to your store's gift list on the fly. 

Magento 2 Free Gifts will add to your customers' shopping journey a surprise and delight moment. Notably, **Magento Free Gifts GraphQL is a part of the Mageplaza Free Gift extension that adds GraphQL features; this supports PWA studio.** 

## 1. How to install
Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-free-gifts-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
**Note:** Magento 2 Free Gifts GraphQL requires installing [Mageplaza Free Gifts](https://www.mageplaza.com/magento-2-free-gifts/) in your Magento installation. 

## 2. How to use

 To start using **Freegifts GraphQL** in your Magento 2 store, you need to:
 
- Use Magento 2.3.x or higher. Return your site to developer mode
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
- Perform a query in the left cell then click the **Run** button or **Ctrl + Enter** to see the result in the right cell
- Currently, Mageplaza Free Gifts extension support the following queries and mutations:
  - Query `mpFreeGiftsByProductSku`: Help to get the free gift by product SKU
  
  ![](https://imgur.com/z84Dsu4.png)
  - Query `mpFreeGiftsByQuoteItem`: Help to get the free gift by quote item Id
  
  ![](https://imgur.com/1UaIjPY.png)
  - Mutation `mpFreeGiftsAddByGiftId`: Help to add free gift by gift Id
  
  ![](https://imgur.com/eoshg4U.png)
  - Mutations `mpFreeGiftsDeleteByQuoteItem`: Help to delete free gift by quote item Id
  
  ![](https://imgur.com/MnJUP2v.png)
  
## 3. Devdocs
- [Magento 2 Free Gifts API & examples](https://documenter.getpostman.com/view/10589000/SzRyzpwv?version=latest)
- [Magento 2 Free Gifts GraphQL & examples](https://documenter.getpostman.com/view/10589000/SzRyzpwr?version=latest)

Click on Run in Postman to add these collections to your workspace quickly. 

![Magento 2 blog graphql pwa](https://i.imgur.com/lhsXlUR.gif)

## 4. Documentation

- Installation guide: https://www.mageplaza.com/install-magento-2-extension/#solution-1-ready-to-paste
- User guide: https://docs.mageplaza.com/free-gifts/
- Report a security issue to security@mageplaza.com

## 5. Contribute to this module
Feel free to **Fork** and contribute to this module. 
You can also create a pull request, so we will merge your changes in the main branch. 

## 6. Get Support
- Feel free to contact us if you have any further questions. 
- If you find this project helpful, please give us a **Star** ![star](https://i.imgur.com/S8e0ctO.png)

