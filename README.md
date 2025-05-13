# ngcleanup
Cleanup Module
Author: Thomas Rothe
Mail: support@netz-giraffe.de

**Important note:**
This module requires:
guest statistic module
connection statistic module
ps_facetedsearch

**Use this module with Prestashop Cronjob** 
Install https://www.silbersaiten.de/de/administrationstools/365-prestashop-cronjobs-prestashop-modul.html
setup your prestashop cron like you want.
see screenshot in the module folder for more informations.

## clearTables
This module truncate the prestashop tables
*guest*<br>
*connections*<br>
*connections_source*<br>
which are in use for statistic reasons.
Use this modul, if you do not need this data.

## triggerFacetedSearchCrons
because of product updates and facette frontend cache (if active), the module is triggering the crons of ps_facetedsearch and clear the facette cache

## clearFrontendCache
clear regularly cache