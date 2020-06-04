## 846-backend

## Demo
JSON Restful API

HTTP GET https://846policebrutality.b-cdn.net/api/incidents
### Front-end Repository
The frontend repository can be found here: https://github.com/mnlmaier/846-frontend

## Roadmap

Incidents Endpoint
- [x] `HTTP GET /api/incidents` - list all incidents
- [ ] `HTTP GET /api/incidents?xx` - Filter by city, state, date range
- [ ] *Any requested end-points?*

Geolocation
- [x] Artisan command to update lat/long for all Incidents
- [ ] Automatically find lat/long on data import

Data Aggregation
- [ ] Continuous Integration of data via MD files from 2020PB/police-brutality

Data Management
- [ ] *Is there value in a CRM?*


## Commit message conventions
```
<type>(<scope>): <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

`feat(test): i am a message`

* feat: A new feature
* fix: A bug fix
* docs: Documentation only changes
* style: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
* refactor: A code change that neither fixes a bug nor adds a feature
* perf: A code change that improves performance
* test: Adding missing or correcting existing tests
* chore: Changes to the build process or auxiliary tools and libraries such as documentation generation
