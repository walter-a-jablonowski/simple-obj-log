# SimpleObjLog

***Currently in dev:*** only arrays can be passed

Instead of just plain text logs should countain data. Best would be a log saves objects of different types,
that have shared columns like time in front. In general graph obj should be prefered, that can be linked
freely (always best). Fix links are a limitation. Types could share more common cols in front.

Save in standard formats instaead proprietary log file format: just as csv, json, yaml or Db table. This makes
processing data with standard tools easy.

```
composer update
```

- CSV and YML have less structure than a graph we only can print what is possible in these formats
  - CSV: one level only
  - YML: a tree which is a specialised version of a graph
- Pass full objects, e.g. event object which has all data needed
  - lib adds basic additional info like: type and if configured time
- Log a graph by having an obj member (array) that has all linked obj
  - array with definable member
  - or array with definable link class and member


## Advanced

maybe ...

- [ ] Add type can be from a field in array
- [ ] Seriailise objects using Reflection (type will be obj type)
  - type should stay first param
- [ ] ~~Print normal member associations just as #id~~ (too much)


**CSV only prints first level, no links**

we could infact print one level inline `field; {linked: obj-data}`

```csv
time, #3421, data, #3253
time, #3253, data, ...
```

**YML prints one level of linked data**

only one level of linked fields, more might be too much for now

```yaml
time: [ log: data ]
time:
  data: [ log: data ]
  linked:
    time: [ log: data ]
```


## LICENSE

Copyright (C) Walter A. Jablonowski 2021, MIT [License](LICENSE)

Licenses of third party software used in samples see [credits](credits.md).

[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)
