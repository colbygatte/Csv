# Quick Start

Composer:
`composer require colbygatte/csv`

```php
<?php
# data.csv:
# state,location
# Louisiana,south
# "New York",north
foreach (csv_sip('data.csv') as $row) {
    # Access row data using that column's header
    if ($row->location == 'south') {
        echo "Hey, y'all! {$row->state} is awesome!";
    } else {
        echo "I don't know much about {$row->state}.";
    }
}
```