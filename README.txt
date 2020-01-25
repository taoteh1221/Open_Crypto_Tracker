
################################################################################################################


DFD Cryptocoin Values - Developed by Michael Kilday <mike@dragonfrugal.com>, released free / open source (under GPL v3)

Copyright 2014-2020 GPLv3

Open source / free private cryptocurrency investment portfolio tracker, with email / text / Alexa / Google Home alerts, charts, mining calculators, leverage / gain / loss / balance stats, and other crypto tools. 

Privately track your investment in Bitcoin, Ethereum, Monero, Litecoin, Grin, Cosmos, and an unlimited number of other altcoins / cryptocurrencies. Customize the coin list / alerts / charts to your favorite assets and exchange pairings. No limits, add as many coins / markets as you want. 

Project Website: https://dfd-cryptocoin-values.sourceforge.io

LIVE PUBLIC DEMO: https://dragonfrugal.com/coin-prices

Download Latest Version: https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases


################################################################################################################


To install / upgrade everything automatically on a Raspberry Pi (an affordable low power single board computer), copy / paste / run the command below in a terminal program while logged in on the Raspberry Pi:


wget -O FOLIO-INSTALL.bash https://git.io/JeWWE;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash


Follow the prompts. This automated script gives you the options to: install / setup a PHP web server automatically, download / setup / configure the latest version of the DFD Cryptocoin Values app automatically, setup a cron job automatically (for price alerts / charts), and setup SSH (to update / install web site files remotely to the web server via SFTP) automatically. 

When the auto-install is completed, it will display addresses / logins to access the app (write these down / save them for future use).

For additional documentation, see /DOCUMENTATION-ETC/HELP-FAQ.txt.

################################################################################################################


FEATURES

-Username / password protection for the portfolio interface.

-Automated and user-friendly installation / upgrade script for Raspberry Pi (an affordable low power single board computer) app setup on your home / internal network.

-Cryptocurrency portfolio subtotal summaries, and total portfolio worth (in crypto and your local primary currency), including value gain / loss data (with tracking support for long / short margin leverages), portfolio balance data, and marketcap data.

-Price change alerts by email / text / Alexa / Google Home (configurable alert parameters available).

-Add / edit / delete your own coin list, with your favorite exchanges / market pairings.

-Add / edit / delete your own price alerts and charts for assets / exchanges / market pairings (supports multiple exchanges / market pairings per asset).

-Import / Export your portfolio in CSV (spreadsheet) file format.

-Detailed charts (base values in crypto and your local primary currency), with spot price / 24 hour volume, zooming, and crosshair hovering.

-Switch between light / dark (night mode) theme colors.

-Mining calculators, to determine coin mining profitability (in crypto and your local primary currency, includes electricity costs and pool fees).

-Crypto tools (QR code generator, altcoin trade preview / marketcap calculator in BTC and your local primary currency, etc).

-External resources page, includes links to marketcap stats sites / news sites / wallets / exchanges / block explorers / developer resources / newsletters / podcasts / social media / etc.

-Help page in easy-to-use FAQ format, for common issues (with support / contact links if you need additional assistance).

-Options to add proxies for API requests / SMTP authentication for email sending / displaying system stats in the interface (uptime / load averages / temperature / free disk space / free system memory / portfolio cache size, if available on your device).

-Configuration checking, alerting, logging, and auto-correcting (where possible).

-Detailed error logging and debugging for in-app functions / configuration settings / networking features, to assist with troubleshooting, installation, and configuration of the app (includes any available system hardware / software stats).

-Chart data backup archives and app error / debugging logs sent to your email.


################################################################################################################


Just upload this app's files to your PHP-based web server (with an FTP client like FileZilla) and you should be all set, unless your host is a strict setup related to file writing permissions, in which case the 'cache' directory permissions should be set to '777' chmod on unix / linux systems (or 'readable / writable' on windows systems). The 'backups' directory permissions MAY also need to be set the same as the 'cache' directory permissions. Your web host must have CURL modules activated on your HTTP server. Most web hosting companies provide this "out-of-the-box" already. This app will detect whether or not CURL is setup on your website server. 

See below for additional details on setup, and see /DOCUMENTATION-ETC/HELP-FAQ.txt for tips / troubleshooting FAQs.


################################################################################################################


Setting up a cron job for charts and price alerts by email / mobile phone text / Alexa / Google Home notifications (get notifications sent to you, even when your PC / Laptop is offline): 

If you want to take advantage of cron job based features like charts, chart data backups, price alerts, daily or weekly error log emails / etc, then the file cron.php (located in the primary directory of this app) must be setup as a cron job on your website's web server. 

If you run the automated setup / install script for Raspberry Pi (affordable low power single board computer) devices on home / internal networks, automatic cron job setup is offered as an option during this process. If you are using a full online website host for hosting a TLD website domain name remotely, consult your web server host's documentation or help desk for their particular method of setting up a cron job. 

Note that you should have the cron job run every 5, 10, 15, 20, or 30 minutes 24/7, based on how often you want alerts / any other cron based features to run. Setting up the cron job to run every 15 minutes is the recommended lowest time interval (if set any lower, the free exchange APIs may throttle / block your data requests temporarily on occasion for requesting data too frequently, which can negatively affect your alerts / charts). 


Here is an example cron job command line for reference below (not including any cron parameters your host interface may require), to setup as the "command" within a cron job. Replace system paths in the example with the correct ones for your server (TIP - A very common path to PHP on a server is /usr/bin/php):

/path/to/php -q /home/username/path/to/website/this_app/cron.php


Here is another example of a COMPLETE cron command that can be added by creating the following file (you'll need sudo/root permissions): /etc/cron.d/cryptocoin on a linux-based machine (to run every 15 minutes 24/7)...play it safe and add a newline after it as well if you install examples like these:

*/15 * * * * WEBSITE_USERNAME_GOES_HERE /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1


If your system DOES NOT have /etc/cron.d/ on it, then NEARLY the same format (minus the username) can be installed via the 'crontab -e' command (logged in as the user you want running the cron job):

*/15 * * * * /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1



IMPORTANT CRON JOB NOTES: MAKE SURE YOU ONLY USE EITHER /etc/cron.d/, or 'crontab -e', NOT BOTH...ANY OLD DUPLICATE ENTRIES WILL RUN YOUR CRON JOB TOO OFTEN. If everything is setup properly and the cron job still does NOT run, your particular server may require the cron.php file permissions to be set as 'executable' ('755' chmod on unix / linux systems) to allow running it.


################################################################################################################


Adding / editing / deleting assets and markets in the portfolio assets:

Below is an example for editing your assets / markets into the coin list in the file config.php (located in the primary directory of this app). It's very quick / easy to do (after you get the hang of it, lol). Also see the text file /DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt, for a pre-configured set of default settings and example assets / markets. Contact any supported exchange's help desk if you are unaware of the correct formatting of the trading pair naming you are adding in the configuration file (examples: Kraken has arbitrary Xs inserted in SOME older pair names, HitBTC sometimes has tether pairing without the "T" in the symbol name).


USAGE (ADDING / UPDATING COINS)

Support for trading pairs (contact me to request more): AUD / BOB / BRL / CAD / CHF / COP / EUR / ETH / GBP / HKD / INR / JPY / LTC / MXN / NIS / PKR / RUB / SGD / TRY / TUSD / USD / USDC / USDT / VND / XMR.

Support for exchanges (contact me to request more): bigone / binance & binance_us / bit2c / bitfinex & ethfinex / bitforex / bitflyer / bitlish / bitpanda / bitso / bitstamp  / bittrex & bittrex_global / braziliex / btcmarkets / btcturk / cex / coinbase / coss / cryptofresh / gateio / gemini / graviex / hitbtc / hotbit / huobi / idex / kraken / kucoin / lakebtc / livecoin / localbitcoins / okcoin / okex / poloniex / southxchange / tidebit / tradeogre / tradesatoshi / upbit.

Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH.
 
 
 
 
                    // UPPERCASE_COIN_ABRV_HERE
                    'UPPERCASE_COIN_ABRV_HERE' => array(
                        
                        'coin_name' => 'COIN_NAME_HERE',
                        'marketcap_website_slug' => 'WEBSITE_SLUG_HERE', // Website slug (URL data) on coinmarketcap / coingecko, leave blank if not listed there
                        'market_pairing' => array(
                                                    
                                    'lowercase_pairing_abrv' => array(
                                          'lowercase_exchange1' => 'MARKETIDHERE',
                                          'lowercase_exchange2' => 'pairing/COINSYMBOLHERE',
                                          'lowercase_exchange3' => 'PAIRING-COINSYMBOLHERE',
                                                    ),
                                                    
                                    'eth' => array(
                                          'lowercase_exchange1' => 'MARKETIDHERE',
                                          'lowercase_exchange2' => 'eth/COINSYMBOLHERE',
                                          'lowercase_exchange3' => 'ETH-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'ETHSUBTOKENNAME', // Must be defined in $app_config['eth_subtokens_ico_values'] in config.php
                                                    ),
                                                    
                                          ) // market_pairing END
                        
                    ), // Asset END
                    
      
 
    
 // SEE /DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt FOR A FULL EXAMPLE OF THE CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)
 


################################################################################################################


Questions, feature requests, and bug reports can be filed at the following URLS:

https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues

https://dragonfrugal.com/contact/

Web server setup / install is available for $30 hourly if needed (try the auto-install bash script first). PM me on Twitter / Skype @ taoteh1221, or contact me using above contact links.


################################################################################################################


Donations support further development... 

GITHUB:    https://github.com/sponsors/taoteh1221

COINBASE:  https://commerce.coinbase.com/checkout/5e72fe35-752e-4a65-a4c3-2d49d73f2c36

PAYPAL:    https://www.paypal.me/dragonfrugal

PATREON:   https://www.patreon.com/dragonfrugal

Monero (XMR): 47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu

      
################################################################################################################              





