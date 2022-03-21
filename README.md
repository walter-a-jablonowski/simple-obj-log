# SimpleObjLog

***Currently in dev:*** only arrays can be passed

Instead of just plain text logs should contain data. Best would be a log saves objects of different types,
that have shared type and may have more columns like time or id in front. It saves in standard formats instead proprietary
log file format: csv, json, yml or DB table. This makes processing data with standard tools easy.

In general graph obj should be prefered, that can be linked freely (always best). Fix links are limitations.
CSV and YML have less structure than a graph we only can print what is possible. Basically a tree can be printed
with a root obj in the main record and nested obj (see also below). More links can only be printed as ids (possible
future version).

```
composer update
```

- Pass full obj, e.g. event object which has all data needed
  - lib adds only basic common fields if configured: time and type in front
- Multiple logs in a file: manually add a column with log identifier
- Misc shared cols and place column the same
- Foreign id's (unimplemented)


## CSV

obj that isn't in from linkedGraph field are printed as ids (currently uninplemented)

```php
$log->setConfig(['fillCSV' => 15]);  // fill csv at least 15 fields
```

```csv
time; type;   id;    data; someid; linkedGraph
time; MyType; #3421; data; #3253;  [[obj-data ...}, ...]
time; MyType; #3253; data; ...
```


## YML

unimplemented, entries sorted by place, time

```yaml
place 1:
  place 2:
    time:
      type: MyType
      id:   "#1234"
      my:   data
      linkedGraph:
        - time: [ my: data ]
          ...
    place 3:
    ...
```


## Advanced

maybe ...

- [ ] sequence for id, maybe use $log prefix (multiple)
- [x] Type from a field in array
  - type must be param in log?
- [ ] Serialise $obj using Reflection (whole graph)
- [ ] Provide a michanism that makes unique ids from obj in cols that aren't linkedGraph
- [ ] Print obj that isn't in from linkedGraph field as ids
  - we also need the id in linkedGraph
- [ ] Use link class see config


## LICENSE

Copyright (C) Walter A. Jablonowski 2021-2022, MIT [License](LICENSE)

Licenses of third party software used in samples see [credits](credits.md).

[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)
