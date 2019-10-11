
################################################################################################################


DFD Cryptocoin Values - Developed by Michael Kilday <mike@dragonfrugal.com>, released free / open source (under GPL v3)

Copyright 2014-2019 GPLv3

Open source / free private cryptocurrency investment portfolio tracker, with email / text / Alexa alerts, charts, mining calculators, leverage / gain / loss / balance stats, and other crypto tools. 

Privately track your investment in Bitcoin, Ethereum, Monero, Litecoin, Grin, Cosmos, and an unlimited number of other altcoins / cryptocurrencies. Customize the coin list / alerts / charts to your favorite assets and exchange pairings. No limits, add as many coins / markets as you want. 

Project Website: https://dfd-cryptocoin-values.sourceforge.io

LIVE PUBLIC DEMO: https://dragonfrugal.com/coin-prices

Download Latest Version: https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases


################################################################################################################


To install / upgrade everything automatically on a Raspberry Pi (an affordable low power single board computer), copy / paste / run the command below in a terminal program while logged in on the Raspberry Pi:


wget -O FOLIO-INSTALL.bash https://git.io/JeWWE;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash


Follow the prompts. This automated script gives you the options to: install / setup a PHP web server automatically, download / setup / configure the latest version of the DFD Cryptocoin Values app automatically, setup a cron job automatically (for price alerts / charts), and setup SSH (to update / install web site files remotely to the web server via SFTP) automatically. When that's all completed, it will display addresses / logins to access the app (write these down / save them for future use).


################################################################################################################


FEATURES

-Automated and user-friendly installation / upgrade script for Raspberry Pi (an affordable low power single board computer) setup on your home / internal network.
Cryptocurrency portfolio subtotal summaries, and total portfolio worth (in Crypto and USD), including value gain / loss data (with tracking support for long / short margin leverages up to 100x), portfolio balance data, and marketcap data.

-Price change alerts by email / text / Alexa (configurable alert parameters available).

-Add / edit / delete your own coin list, with your favorite exchanges / market pairings.

-Add / edit / delete your own price alerts and charts for assets / exchanges / market pairings (supports multiple exchanges / market pairings per asset).

-Import / Export your portfolio in CSV (spreadsheet) file format.

-Detailed charts (base values in Crypto and USD), with spot price / volume, zooming, and crosshair hovering.

-Switch between light / dark (night mode) theme colors.

-Mining calculators, to determine coin mining profitability (includes electricity costs and pool fees).

-Crypto tools (QR code generator, altcoin trade preview / marketcap calculator in BTC and USD, etc).

-External resources page, includes links to marketcap stats sites / news sites / wallets / exchanges / block explorers / developer resources / newsletters / podcasts / social media / etc.

-Help page in easy-to-use FAQ format, for common issues (with support / contact links if you need additional assistance).

-Options to add proxies for API requests / SMTP authentication for email sending.

-Detailed error logging for in-app functions / configuration settings / networking features, to assist with troubleshooting, installation, and configuration of the app.

-Chart data backup archives and app error logs sent to your email.


################################################################################################################


Questions, feature requests, and bug reports can be filed at the following URLS:

https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues

https://dragonfrugal.com/contact/

Web server setup / install is available for $30 hourly if needed. PM me on Twitter / Skype @ taoteh1221, or contact me using above contact links.


################################################################################################################


Donations support further development... 

PAYPAL: https://www.paypal.me/dragonfrugal

PATREON: https://www.patreon.com/dragonfrugal

Monero (XMR): 47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu


################################################################################################################


Just upload this app's files to your PHP-based web server (with an FTP client like FileZilla) and you should be all set, unless your host is a strict setup related to file writing permissions, in which case the 'cache' directory permissions should be set to '777' chmod on unix / linux systems (or 'readable / writable' on windows systems). Your web host must have CURL modules activated on your HTTP server. Most web hosting companies provide this "out-of-the-box" already. This app will detect whether or not CURL is setup on your website server. 

See below for additional details on setup, and see HELP-FAQ.txt for tips / troubleshooting FAQs.


################################################################################################################


Setting up a cron job for charts and asset price alerts by email / mobile phone text / amazon alexa notifications (get notifications sent to you, even when your PC / Laptop is offline): 

If you want to take advantage of cron job based features like charts, chart data backups, asset price alerts, daily or weekly error log emails / etc, then the file cron.php (located in the primary directory of this app) must be setup as a cron job on your website's web server. 

If you run the automated setup / install script for Raspberry Pi (affordable low power single board computer) devices on home / internal networks, automatic cron job setup is offered as an option during this process. If you are using a full online website host for hosting a TLD website domain name remotely, consult your web server host's documentation or help desk for their particular method of setting up a cron job. 

Note that you should have the cron job run every 5, 10, 15, 20, or 30 minutes 24/7, based on how often you want alerts / any other cron based features to run. Setting up the cron job to run every 15 minutes is the recommended lowest time interval (if set any lower, the free exchange APIs may throttle / block your data requests temporarily on occasion for requesting data too frequently, which can negatively affect your alerts / charts). 


Here is an example cron job command line for reference below (not including any cron parameters your host interface may require), to setup as the "command" within a cron job. Replace system paths in the example with the correct ones for your server (TIP - A very common path to PHP on a server is /usr/bin/php):

/path/to/php -q /home/username/path/to/website/this_app/cron.php


Here is another example of a COMPLETE cron command that can be added by creating the following file (you'll need sudo/root permissions): /etc/cron.d/cryptocoin on a linux-based machine (to run every 15 minutes 24/7)...play it safe and add a newline after it as well if you install examples like these:

*/15 * * * * WEBSITE_USERNAME_GOES_HERE /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1


If your system doesn't have /etc/cron.d/ on it, then roughly the same format can be installed via the 'crontab -e' command (logged in as the user you want running the cron job):

*/15 * * * * /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1



IMPORTANT NOTE: If everything is setup properly and the cron job still does NOT run, your particular server may require the cron.php file permissions to be set as 'executable' ('755' chmod on unix / linux systems) to allow running it.


################################################################################################################


Adding / editing / deleting assets and markets in the coins list:

Below is an example for editing your assets / markets into the coin list in the file config.php (located in the primary directory of this app). It's very quick / easy to do (after you get the hang of it, lol). Also see further down in this README for a pre-configured set of default settings and example assets / markets. Currently BTC / XMR / ETH / LTC / USDT (Tether) / TUSD (True USD) / USDC base pairing is supported. Contact any supported exchange's help desk if you are unaware of the correct formatting of the trading pair naming you are adding in the configuration file (examples: Kraken has arbitrary Xs inserted in SOME older pair names, HitBTC sometimes has tether pairing without the "T" in the symbol name).


 * USAGE (ADDING / UPDATING COINS) ...API support for (contact me to request more): coinbase / binance / bittrex and bittrex_intl / kraken / poloniex / bitstamp / bitfinex and ethfinex / cryptofresh / gemini / hitbtc / livecoin / upbit / kucoin / okex / gateio / graviex / idex / hotbit / tradeogre / bitforex / bigone / tradesatoshi...BTC, XMR, ETH, LTC, USDT, TUSD, and USDC trading pair support
 * Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH
 
 
 
 
                    // UPPERCASE_COIN_SYMBOL
                    'UPPERCASE_COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'marketcap_website_slug' => 'WEBSITE_SLUG', // Website slug (URL data) on coinmarketcap / coingecko, leave blank if not listed there
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'BTC-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'xmr' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'XMR_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'XMR-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'eth' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'ETH_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'ETH-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'ETHSUBTOKENNAME' // Must be defined in $eth_subtokens_ico_values in config.php
                                                    ),
                                                    
                                    'ltc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'LTC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'LTC-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'usdt' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'USDT_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'USDT-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'tusd' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'TUSD_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'TUSD-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'usdc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'USDC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'USDC-COINSYMBOLHERE'
                                                    )
                                                    
                                          ) // market_pairing END
                        
                    ), // Coin END
                    
      
 
    
 // SEE CONFIG.EXAMPLE.txt FOR A FULL EXAMPLE OF THE CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)
 
      
################################################################################################################              





