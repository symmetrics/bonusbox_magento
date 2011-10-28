# Bonusbox's magento test systems

The sql file is based on version 1.4.0.0 and can be upgraded to every magento version (this is done on the first request and takes several seconds). 
It contains the required configuration and minimum data for a bonunsbox demo. 
Replace all occurences of local.magento/1.4.0.0 with the base url without "http://" in the sql file. 
Open the file "local.xml", copy it to app/etc/local.xml and set the database connection params. 
Run http://path-to-magento/bonusbox/test/success_page.   