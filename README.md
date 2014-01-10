BptApi
========

A PHP class with methods to interact with the Brown Paper Tickets API.

Usage
-----

In order to use this class you must have developer tools added to your
BPT account. 

To add those tools log into Brown Paper Tickets and go to 
[Account Functions](https://www.brownpapertickets.com/user/functions.html).

Simply require the bpt class and create a new instance of it.

```php

require('bpt_api.php');

$bpt = new BptApi;

```

Methods
-------
** All methods require the dev_id. All methods return a multidimensional 
array with the requested data.**

### event_list_call($dev_id[string], $client_id[int], $event_id[int], $dates[boolean], $prices[boolean])

$event_id is optional. Pass along an event ID there if you want info 
on that specific event.

By default, $dates and $prices is set to false so it won't return any of
that info. If you want dates, pass those arguments as true.

Example:

```php

require('bpt_api.php');

$bpt = new BptApi;

$events = $bpt->event_list_call('a_developer_id', 123456, true, true');
```

### date_list_call($dev_id[string], $event_id[int], $date_id[int], $prices[boolean])

Again, by default it won't load prices so if you want to pull all of the
prices attached to this date, just pass true.

```php

require('bpt_api.php');
$bpt = new BptApi;

$dates = $bpt->date_list_call('a developer id', 123456, 123456, true)
```

### price_list_call($dev_id[string], $event_id[int], $date_id[int])

All of those fields are required.


More Real Life Example
----------------------

```php
<?php 
    
    require('bpt_php.php');
    $bptAPI = new BptApi;
    $sales = $bptAPI->sales_list_call('devid', 'username', 123456);

?>
<!DOCTYPE html>
<html>
<head>
<title>Sales List Test!</title>

</head>
<body>
    <h1>Sales List</h1>
    <table>
        <tr>
            <th>Order Time</th>
            <th>Date ID</th>
            <th>Price ID</th>
            <th>Quantity</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
            <th>Country</th>
            <th>E-Mail</th>
            <th>Phone</th>
            <th>Last 4 of CC</th>
            <th>Shipping</th>
            <th>Order Notes</th>
            <th>Ticket Number</th>
            <th>Section</th>
            <th>Row</th>
            <th>Seat</th> 
        </tr>
        <?php 
            foreach ($sales as $single_sale ) {
                echo "<tr>";

                foreach($single_sale as $sale_data) {
                    echo '<td>'.$sale_data.'</td>';
                }

                echo "</tr>";
            }
        ?>
        </tr>
    </table>

</body>
</html>
```