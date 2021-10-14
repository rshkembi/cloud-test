# stover_default theme

### Author(s)

```
Kevin Gardner <kgardner@human-element.com>
Caitlin Bartley <cbartley@human-element.com>
```

### Theme Notes:

```
[Add any important theme notes here.]
```

### Grunt - LESS compiling

- grunt is installed and added to this repository. If using Work8space Manager, grunt is already installed via the shared library. Confirm with `which grunt`. If not present, run `npm install --global grunt`
  
- local-themes.js is configured to compile theme(s) 
- Set local environment to developer mode. `grunt exec` when starting to map all less source files. `grunt watch` for an interactive mode where grunt monitors and runs on file save. `grunt clean` to clean out all mappings. Run `grunt less` after grunt clean for best results.
