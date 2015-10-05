Integrating WebORB for PHP with Drupal.

1. Install and configure Drupal: http://drupal.org/project/drupal
2. Install Services modules: http://drupal.org/project/services
3. Copy the contents of the [WEBORB INSTALL]/Modules/Drupal/ folder into [DRUPAL HOME]/modules 
   (As a result of step 3, you should have [DRUPAL HOME]/modules/weborb folder with some files in it)
4. Copy the following folders from [WEBORB INSTALL] into [DRUPAL HOME]/modules/weborb/weborb 
   (double "weborb" in the path is not a typo)
   [WEBORB INSTALL]/Services
   [WEBORB INSTALL]/Weborb
5. Enable weborb module in Drupal on the following page: Administer -> Site Building -> Modules
6. Confirm that WebORB is working. Open Administer -> Site Building -> Services. 
   Click "WebORB - /services/weborb". If you see a page showing "WebORB v3.5.0", the integration is successful.