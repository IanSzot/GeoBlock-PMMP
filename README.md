# GeoBlock-PMMP

GeoBlock is a very simple Geo Blocking plugin for PocketMine-MP


## How does it work?

Everytime a player join your server his IP will be checked against external APIs so we can get his country of origin and block or allow his login

## APIs used and limits
This plugin uses two APIs. One main API (ip-api.com) and another for failover (extreme-ip-lookup.com).
ip-api.com has a hard limit of 150 requests per minute and extreme-ip-lookup.com has a limit of 50 request per limit. This means that you have 200 requests per minute avaliable for you.

Note: ip-api.com WILL block your IP if you send more than 150 requests per minute and you will need to unban your IP [here](http://ip-api.com/docs/unban) or that will leave you with only 50 requests per minute from extreme-ip-lookup.com.

**One request = one player logging in on the server**. If have a big server (more than 150 players logging in per minute) please consider actually paying for some GeoIP API and edit the code to suit your new paid API

## Permissions

|Permission|Description  |
|--|--|
| geoblock.bypass |Players with this permission will bypass Geo Block  |


## config.yml

Configuration is pretty straightforward.

| Setting   | Description                                                                  |
|-----------|------------------------------------------------------------------------------|
| **kickMsg**   |                           Message for kicked player                          |
| **action**    | deny or allow                                                                |
|           | allow = only players from “countries” will be able to join                   |
|           | deny = every player will join except players from “countries”                |
| **countries** | Countries to allow/deny on the server. Uppsercase comma separated            |
|           | Example: [BR, NZ, CN, RU, US]                                                |
| **fail**      | deny or allow. What to do if all APIs fail to return a country?              |
|           | allow = players with invalid country code will be able to join the server    |
|           | deny = players with invalid country code WILL NOT be able to join the server |
| **failMsg**   | Message to show players kicked due to API fail                               |


## IMPORTANT
Again, I'd like to remind that if you get many (over 150) players logging in per minute it's VERY recommended that you pay for a good unlimited GeoIP API, there are many on Google, search for one that best suit your needs and budget. You will also have to edit the code to suit your new API.


*What happens if all APIs go offline or my IP gets blacklisted?*

Good question. I don't know. I guess players will be stuck in the logging in screen for a while...

*Does this detect VPNs?*

No. Players with a proxy or VPN can easily bypass this plugin
