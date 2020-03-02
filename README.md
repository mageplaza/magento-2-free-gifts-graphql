# Free Gifts GraphQl Extension

## How to install
Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-free-gifts-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## How to use

 To start using **Freegifts GraphQL** in your Magento 2 store, you need to:
 
- Use Magento 2.3.x. Return your site to developer mode
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers)
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
- Perform a query in the left cell then click the **Run** button or **Ctrl + Enter** to see the result in the right cell
- Currently, Mageplaza Free Gifts extension support the following queries and mutations:
  - Query `mpFreeGiftsByProductSku`: Help to get the free gift by product SKU
  
  ![](https://imgur.com/z84Dsu4.png)
  - Query `mpFreeGiftsByQuoteItem`: Help to get the free gift by quote item Id
  
  ![](https://imgur.com/1UaIjPY.png)
  - Mutation `mpFreeGiftsAddByGiftId`: Help to add free gift by gift Id
  
  ![](https://imgur.com/eoshg4U.png)
  - Mutations `mpFreeGiftsDeleteByQuoteItem`: Help to add free gift by quote item Id
  
  ![](https://imgur.com/MnJUP2v.png)

## Documentation

- Installation guide: https://www.mageplaza.com/install-magento-2-extension/#solution-1-ready-to-paste
- User guide: https://docs.mageplaza.com/free-gifts/
- Report a security issue to security@mageplaza.com
