### _For a full waiver of 2Checkout's $49 signup fee, enter promo code:  GIT2CO  during signup._

2Checkout INS Simulator for Demo Sales
-------------------------------------------
****

This script was made to assist you with simulating INS messages on demo
sales.


### Why do I need it?
Because 2Checkout does not send INS message on demo sales and the INS
simulator does not use your sale data that you application will be looking
for.


### How does it work?
The return.php script works by taking the parameters sent to the approved URL and
converting the values to the appropriate INS parameters. The INS array is then
saved in a JSON file and retrieved by the testing tool. The original return
parameters are then forwarded to your approved URL by GET or POST based
on the return method that the you used.

The test.php script retrieves the JSON, takes secret word so that the the MD5 hash
can be computed correctly and adds the fields to an HTML form so that
you can edit the values if necessary for specific test cases. You can then
POST the parameters to your INS URL through the form or using cURL if you
prefer by selecting cURL as the return type.


### Usage
To use, just download or clone, upload to your server and on your 2Checkout
Site Management page set the approved URL to point to the included response.php
file.

_Please note that the the 2Checkout approved URL passback returns different
data for different parameters sets so while I tried to get everything as close
as possible, you may need to amend some parameter values that are not
returned._

**2Checkout INS Documentation**
https://www.2checkout.com/static/va/documentation/INS/index.html
