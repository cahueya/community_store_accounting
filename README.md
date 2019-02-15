# Electronic Accounting for concrete5 Community Store

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

An addition to the concrete5 community store (https://github.com/concrete5-community-store/community_store) that allows the submission of successfull paid Orders to a Accounting handler via Email with XML attachment or by CURL request.

## Installation
You must install the community store before installing this addon.

## Current Version
The current version is scoped towards the needs of Italian eAccounting, that is why there are some configuration values (PEC, codice destinatorio) that may be unknown. I plan to localize the package in a way that only those values are shown that are needed in the tax system of where the shop owners reside.

## What it does
I have added some comments to /src/AccountingProcess.php where most of the work happens.
Those are the steps:
### 1. Get data
At first, the function loops through the CommunityStoreOrders table and looks for the Orders that have a oPaid date set which means that their payment is completed. By setting the "startOrder" Value in the configuration, you can define to ignore Orders if they are already processed before the package was installed. Of the paid Orders, the oID (Order ID) will be inserted into the CommunityStoreAccounting table. Also, all the OrderIDs that are already in the CommunityStoreAccounting table will be ignored because they have already been processed.

### 2. Get the details
Now for every orderID that has been collected, the OrderObject is retrieved so that the individual values can be grabbed and used for submission.

### 3. Email Method
If you chose the Email method in the config, the Order data will now be used to build a file. The goal is to build a XML file that is formatted in the way the tax law requires. This file shall be saved to /applications/files/tmp and in the next step, imported to the FileManager, because we need its FileID. Then the original file in the /tmp directory will be deleted and the newly imported file will be identified by its FileID. With that, we can attach it to a Mail.

### 4. API Method
If you chose the API method, the XML file should be parsed into a CURL request and sent to the Accounting Service of which you entered the Credentials in Config. As some Accounting Services require Username:Password auth and some work with API Key, this needs to be solved.

### 5. Logging
After the CURL request is sent, it will respond with $response which will either be a success message or an error. A success message will insert the current timestamp next to the OrderID to protocoll the success. If the returning message is an Error, we insert that also.
