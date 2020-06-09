## 8:46 police brutality :clock830: :rotating_light:

8 minutes and 46 seconds is the length of time associated with the killing of George Floyd, who died in police custody after police officer Derek Chauvin knelt on his neck for roughly eight minutes.

This repo provides and API and archives acts of assault by public servants to American Citizens during non-violent acts of protest. 

---------------------------

#### API

**List all catalog Incidents of Police Assault**
 
`GET` https://api.846policebrutality.com/api/incidents

**List all Incidents and include Evidence (such as Video)**

`GET` https://api.846policebrutality.com/api/incidents?include=evidence

**Show a Single Incident**

`GET` https://api.846policebrutality.com/api/incidents/{id}

You can also include the Evidence model:

`GET` https://api.846policebrutality.com/api/incidents/{id}?include=evidence

---------------------------

#### Data Visualization and Front-end Websites

**Map**

https://846policebrutality.com/ - Uses this API, currently slightly lagged as the parent data-feed doesn't include static ids

https://policebrutality.me/ - Uses this API, shows incidents and videos/photos on map

**Filter by State**

https://2020policebrutality.netlify.app/ - Uses parent repo's data feed, real time.

---------------------------

#### Reference Repos

This repo aggregates data from a variety of source, most notably:
 
 [2020PB/police-brutality](https://github.com/2020PB/police-brutality) - The JSON data feed made available
 

[mnlmaier/846-frontend](https://github.com/mnlmaier/846-frontend) - A front-end app to visual this data

---------------------------

#### About This Project

This project was inspired by  [2020PB/police-brutality](https://github.com/2020PB/police-brutality). Therefore:

This repository exists to accumulate and contextualize evidence of police brutality during the 2020 George Floyd protests.

Our goal in doing this is to assist journalists, politicians, prosecutors, activists and concerned citizens who can use the evidence accumulated here for political campaigns, news reporting, public education and prosecution of criminal police officers.

If you wish to contribute, please start by reading the [contribution guidelines](https://github.com/2020PB/police-brutality).

---

#### More about \*this\* repo

* This project does not condone acts aggression of any parties
* This project is meant to enable others to share their voice and stand-up against acts of violence by public service
* This project intends to fight censorship by encouraging all to get involved and mirror this data, download the media, and fight for progress
* This project is not anti-police
* This project is a public work dedicated to all of humanity, regardless of race, creed, or borders. 

## Roadmap

Incidents Endpoint
- [x] `HTTP GET /api/incidents` - list all incidents
- [x] `HTTP GET /api/incidents?include=evidence` - Include video evidence
- [ ] `HTTP GET /api/incidents?xx` - Filter by city, state, date range
- [ ] *Any requested end-points?*

Video
- [x] Artisan command to pull videos from Incident websites

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
