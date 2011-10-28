# Bonusbox's magento test systems

Each sql file corresponds to a specific magento version 1.x.y.z. Replace all occurences of local.magento/1.x.y.z with the base url without "http://" in the sql file (e.g. for 1.4.0.0 replace "local.magento/1.4.0.0" with your base url). 
Open the file "local.xml", copy it to app/etc/local.xml and set the database connection params. 
Run http://path-to-magento/bonusbox/test/success_page.   