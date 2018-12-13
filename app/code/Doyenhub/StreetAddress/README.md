# Magento 2 module

**Magento 2 Module development** or **Magento 2 StreetAddress Module** trends is increase rapidly while Magento release official version. That why we - **Doyenhub** - are wring about a topic that introduces how to create a simple **StreetAddress module in Magento 2**.
As you know, the module is a  directory that contains `blocks, controllers, models, helper`, etc - that are related to a specific business feature. In Magento 2, modules will be live in `app/code` directory of a Magento installation, with this format: `app/code/<Vendor>/<ModuleName>`. Now we will follow this steps to setup module which work on Magento 2.



## Magento 2 StreetAddress module by store.doyenhub.com

In this file, we register a enable `Doyenhub_StreetAddress` and the version is `1.0.0`.


### Step 1. Enable the module

Before enable the module, we must check to make sure Magento has recognize our module or not by enter the following at the command line:

~~~
php bin/magento module:status
~~~

If you follow above step, you will see this in the result:

~~~
List of disabled modules:
Doyenhub_StreetAddress
~~~

This means the module has recognized by the system but it is still disabled. Run this command to enable it:

~~~
php bin/magento module:enable Doyenhub_StreetAddress
~~~

The module has enabled successfully if you saw this result:

~~~
The following modules has been enabled:
- Doyenhub_StreetAddress
~~~

This’s the first time you enable this module so Magento require to check and upgrade module database. We need to run this comment:

~~~
php bin/magento setup:upgrade
~~~

Now you can check under `Stores -> Configuration -> Advanced -> Advanced` that the module is present.
