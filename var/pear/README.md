# Package Extens
 * Login in to http://magento.bonusbox.me/magento-head/admin
 * Go to System > Magento Connect > Package Extensions
 * Go to "Load local Package" and select Bonusbox_Bonusbox
 * Go to "Release Info" and increase the "Release Version" (you cannot upload an extension with the same version more than once)
 * Click "Save data Create Package"
 * Copy the file magento-plugin/var/pear/Bonusbox_Bonusbox.xml to your local working copy and push it
 * Location of created package in magento-plugin/var/pear/Bonusbox_Bonusbox-{Version}.tgz
 
# Package Extension Upload to Magento Connect
 * Go to https://www.magentocommerce.com/magento-connect/extension/extension/list/ and login in
 * Select "Edit" bonusbox in "My Extensions"
 * Go to "Versions" and click "Add new Version"
 * Fill in the same version number and stability that you defined for your extension package
 * Release Title and Notes have no restrictions
 * select the magento versions 1.4 - 1.6.1
 * Click "Continue to upload"
 * Search tgz-File and click "Upload and Save"