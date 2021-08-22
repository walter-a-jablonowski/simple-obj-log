# SimpleObjLog

***Currently in dev:*** only arrays can be passed

Instead of just plain text logs should contain data. Best would be a log saves objects of different types,
that have shared columns like time in front. It saves in standard formats instead proprietary log file format:
csv, json, yaml or Db table. This makes processing data with standard tools easy.

In general graph obj should be prefered, that can be linked freely (always best). Fix links are a limitation.
CSV and YML have less structure than a graph we only can print what is possible. Basically a tree can be printed
with a root obj in the main record and nested obj (see also below). More links can only be printed as ids (possible
future version).

```
composer update
```

- Pass full objects, e.g. event object which has all data needed
  - lib adds only basic common fields if configured: time and type in front
- Multiple logs in a file: manually add a column with log identifier
- Misc shared columns the same


## Advanced

maybe ...

- [ ] Type from a field in array
- [ ] Seriailise objects using Reflection
- [ ] Print normal member associations and loops in embedded obj as #id
  - we also need the id in printed obj
- [ ] Use link class see config


**CSV only prints first level, no links**

- Currenlty only links fields will be serialised
- serialise more yourself

```csv
time, MyType, #3421, data, #3253, {linked: obj-data}  # obj ids currently uninplemented
time, MyType, #3253, data, ...
```

**YML prints one level of linked data**

```yaml
time: [ type: MyType, log: data ]
time:
  type: MyType
  log:  data
  linked:
    time: [ log: data ]
```


## LICENSE

Copyright (C) Walter A. Jablonowski 2021, MIT [License](LICENSE)

Licenses of third party software used in samples see [credits](credits.md).

[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)
