# Package Extension Creation

 * Login in to http://magento.bonusbox.me/magento1420/admin
 * Go to System > Magento Connect > Package Extensions
 * Go to "Load local Package" and select Bonusbox_Bonusbox
 * Go to "Release Info" and increase the "Release Version" (you cannot upload an extension with the same version more than once)
 * Click "Save data Create Package"
 * Package location in magento-plugin/var/pear/Bonusbox_Bonusbox-{Version}.tgz
 * Commit the changes of magento-plugin/var/pear/Bonusbox_Bonusbox.xml
 
# Package Extension Upload to Magento Connect
 * Go to https://www.magentocommerce.com/magento-connect/extension/extension/list/ and login in
 * Select "Edit" bonusbox in "My Extensions"
 * Go to "Versions" and click "Add new Version"
 * Fill in the same version number and stability that you defined for your extension package
 * Release Title and Notes have no restrictions
 * select the magento version 1.4 - 1.6.1
 * Click "Continue to upload"
 * Search File and click "Upload and Save"