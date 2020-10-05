
############################################################################################################################

DFD Cryptocoin Values - Developed by Michael Kilday <mike@dragonfrugal.com> (Copyright 2014-2020 GPLv3)


Open source / free cryptocurrency investment portfolio tracker, with email / text / Alexa / Ghome / Telegram alerts, charts,  mining calculators, leverage / gain / loss / balance stats, news feeds and more. Privately track Bitcoin, Ethereum, and unlimited other cryptocurrencies. Customize assets list / alerts / charts / exchange pairings. Add as many assets / markets / alerts / charts as you want. 

Web server setup / install is available for $30 hourly if needed (try the auto-install bash script first). PM me on Twitter / Skype @ taoteh1221, or get a hold of me using the below-listed contact methods.


Project Website: https://taoteh1221.github.io

LIVE PUBLIC DEMO: https://dragonfrugal.com/coin-prices

Download Latest Version: https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases

Issue Reporting (Features / Issues / Help): https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues

Discord Chat: https://discord.gg/WZVK2nm

Telegram Chat: https://t.me/joinchat/Oo2XZRS2HsOXSMGejgSO0A

Private Contact: https://dragonfrugal.com/contact


Donations support further development... 

Github Sponsors:  https://github.com/sponsors/taoteh1221

Patreon:   https://www.patreon.com/dragonfrugal

PayPal:    https://www.paypal.me/dragonfrugal

Coinbase:  https://commerce.coinbase.com/checkout/5e72fe35-752e-4a65-a4c3-2d49d73f2c36

Monero (XMR): 47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu


############################################################################################################################


FEATURES

-Automated and user-friendly installation / upgrade script for Ubuntu or Raspberry Pi app setup on your home / internal network or website.

-Support for over 40 exchanges (including DeFi), and over 80 market pairings (country fiat currency or secondary crypto).

-Secure HTTPS (SSL) and username / password protection in the portfolio interface, for privacy and security.

-Admin interface, to allow easily viewing / changing the app configuration (alerts / charts / markets / API / backups / logs / etc).

-Switch between light / dark (night mode) theme colors.

-Cryptocurrency portfolio subtotal summaries, and total portfolio worth (in crypto and your local primary currency), including value gain / loss data (with tracking support for long / short margin leverages), portfolio balance data, and marketcap data.

-Price change alerts by email / text / Alexa / Google Home / Telegram (configurable alert parameters available).

-Add / edit / delete your own portfolio assets list, with your favorite exchanges / market pairings.

-Add / edit / delete your own price alerts and charts for assets / exchanges / market pairings (supports multiple exchanges / market pairings per asset).

-Import / export your portfolio in CSV (spreadsheet) file format.

-A news page with over 60 different cryptocurrency-related RSS feeds to select from, including company and organization blogs / news sites / podcasts / youtube channels / reddit and stackexchange forums.

-External resources page, includes links to marketcap stats sites / news sites / wallets / exchanges / block explorers / developer resources / newsletters / podcasts / social media / etc.

-Detailed charts (base values in crypto and your local primary currency), with spot price / 24 hour volume, zooming, and crosshair hovering.

-Crypto tools (QR code generator, altcoin trade preview / marketcap calculator in BTC and your local primary currency, etc).

-Mining calculators, to determine coin mining profitability (in crypto and your local primary currency, includes electricity costs and pool fees).

-Help page in easy-to-use FAQ format, for common issues (with support / contact links if you need additional assistance).

-System stats in the interface and debug logs (uptime / load averages / temperature / free disk space / used system memory / portfolio cache size, if available on your device).

-Secure webhook capability, allowing other external apps to communicate in real-time safely (separate keys per service, without giving away the master webhook key).

-Internal restful API built-in, to allow other external apps to query real-time market data in over 80 country fiat currencies / secondary crypto pairings (raw data also available).

-Option to use proxies for external API requests, and SMTP authentication for email sending.

-System / configuration checking, alerting, logging, and auto-correcting (where possible).

-Detailed error logging and debugging (with adjustable verbosity / debug modes), to assist with troubleshooting / installation / configuration of the app.

-Chart data backup archives and app error / debugging logs sent to your email.


############################################################################################################################


INSTALLATION AND SETUP

IMPORTANT NOTES: YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA), #WHEN YOU FIRST RUN THIS APP#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#, ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Command to install / upgrade everything automatically on Ubuntu or Raspberry Pi (an affordable low power single board computer)...
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Recommended MINIMUM system specs: 1 Gigahertz CPU / 512 Megabytes RAM / HIGH QUALITY 32 Gigabyte MicroSD card (running Nginx or Apache headless with PHP v7.2+)

wget -O FOLIO-INSTALL.bash https://git.io/JU4gn;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash

Follow the prompts. This automated script gives you the options to: install / uninstall a PHP web server automatically, download / install / configure / uninstall the latest version of the DFD Cryptocoin Values app automatically, setup a cron job automatically (for price alerts / charts), and setup SSH (to update / install web site files remotely to the web server via SFTP) automatically. 

When the auto-install is completed, it will display addresses / logins to access the app (write these down / save them for future use).

SEE /DOCUMENTATION-ETC/RASPBERRY-PI-SECURITY.txt, and /DOCUMENTATION-ETC/RASPBERRY-PI-HEADLESS-WIFI-SSH.txt for additional information on securing and setting up Raspberry Pi OS (disabling bluetooth, firewall setup, remote login, etc).

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Manual installation:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Just upload this app's files to your PHP-based web server (with an FTP client like FileZilla) and you should be all set, unless your host is a strict setup related to file writing permissions, in which case the 'cache' directory permissions should be set to '777' chmod on unix / linux systems (or 'readable / writable' on windows systems). Your web host must have CURL modules activated on your HTTP server. Most web hosting companies provide this "out-of-the-box" already. This app will detect whether or not CURL is setup on your website server (and also alert you to any other missing required system components / configurations). 


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Setting up a cron job for charts and price alerts by email / mobile phone text / Alexa / Google Home / Telegram notifications 
(get notifications sent to you, even when your PC / Laptop is offline): 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you want to take advantage of cron job based features like charts, chart data backups, price alerts, daily or weekly error log emails / etc, then the file cron.php (located in the primary directory of this app) must be setup as a cron job on your Ubuntu / Raspberry Pi / website server device. 

If you run the automated setup / install script for Ubuntu or Raspberry Pi (an affordable low power single board computer) devices on home / internal networks, automatic cron job setup is offered as an option during this process. If you are using a full stack website host for hosting a TLD website domain name remotely, consult your web server host's documentation or help desk for their particular method of setting up a cron job. 

Note that you should have the cron job run every 5, 10, 15, 20, or 30 minutes 24/7, based on how often you want chart data points / alerts / any other cron based features to run. Setting up the cron job to run every 20 minutes is the RECOMMENDED lowest time interval. IF SET BELOW 20 MINUTES, lite chart disk writes may be excessive for lower end hardware (Raspberry PI MicroSD cards etc). IF SET #VERY LOW# (5 / 10 minutes), the free exchange APIs may throttle / block your data requests temporarily on occasion for requesting data too frequently (negatively affecting your alerts / charts). 

Here is an example cron job command line for reference below (NOT including any cron parameters your host interface may require), to setup as the "command" within a cron job. Replace system paths in the example with the correct ones for your server (TIP - A very common path to PHP on a server is /usr/bin/php):

/path/to/php -q /home/username/path/to/website/this_app/cron.php

Here is another example of a COMPLETE cron command that can be added by creating the following file (you'll need sudo/root permissions): /etc/cron.d/cryptocoin on a linux-based machine with systemd (to run every 20 minutes 24/7)...play it safe and add a newline after it as well if you install examples like these:

*/20 * * * * WEBSITE_USERNAME_GOES_HERE /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1

If your system DOES NOT have the directory /etc/cron.d/ on it, then NEARLY the same format (minus the username) can be installed via the legacy 'crontab -e' command (YOU MUST BE logged in as the user you want running the cron job):

*/20 * * * * /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1


SEE /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt for creating your own custom cron plugins (custom code that runs at the end of regularly-scheduled cron jobs)


IMPORTANT CRON JOB NOTES: 

MAKE SURE YOU ONLY USE EITHER /etc/cron.d/, or 'crontab -e', NOT BOTH...ANY OLD DUPLICATE CRONTAB ENTRIES WILL RUN YOUR CRON JOB TOO OFTEN. If everything is setup properly, and the cron job still does NOT run, your particular server may require the cron.php file permissions to be set as 'executable' ('755' chmod on unix / linux systems) to allow running it.


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Using the built-in (internal) REST API:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This app has a built-in (internal) REST API available, so other external apps can connect to it and receive market data, including market conversion (converting the market values to their equivalent value in country fiat currencies and secondary cryptocurrency market pairings).

To see a list of the supported assets in the API, use the endpoint: "/api/asset_list"

To see a list of the supported exchanges in the API, use the endpoint: "/api/exchange_list"

To see a list of the supported markets for a particular exchange in the API, use the endpoint: "/api/market_list/[exchange name]"

To see a list of the supported conversion currencies (market values converted to these currency values) in the API, use the endpoint: "/api/conversion_list"

To get raw market values AND also get a market conversion to a supported conversion currency (see ALL requested market values also converted to values in this currency) in the API, use the endpoint: "/api/market_conversion/[conversion currency]/[exchange1-asset1-pairing1],[exchange2-asset2-pairing2],[exchange3-asset3-pairing3]"

To skip conversions and just receive raw market values in the API, you can use the endpoint: "/api/market_conversion/market_only/[exchange1-asset1-pairing1],[exchange2-asset2-pairing2],[exchange3-asset3-pairing3]"

For security, the API requires a key / token to access it. This key must be named "api_key", and must be sent with the "POST" data method.


// SEE /DOCUMENTATION-ETC/REST-API-EXAMPLES.txt FOR EXAMPLES OF CALLING THE API WITH CURL, JAVASCRIPT, AND PHP


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adding / editing / deleting assets and markets in the portfolio assets:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Below is an example for editing your assets / markets into the portfolio assets in the file config.php (located in the primary directory of this app). It's very quick / easy to do (after you get the hang of it, lol). Also see the text file /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt, for a pre-configured set of default settings and example assets / markets. Contact any supported exchange's help desk if you are unaware of the correct formatting of the trading pair naming you are adding in the configuration file (examples: Kraken has arbitrary Xs inserted in SOME older pair names, HitBTC sometimes has tether pairing without the "T" in the symbol name).


USAGE (ADDING / UPDATING COINS)


Support for over 80 trading pairs (country fiat currency or secondary crypto, contact me to request more): 

AED / ARS / AUD / BAM / BDT / BOB / BRL / BTC / BWP / BYN / CAD / CHF / CLP / CNY / COP / CRC / CZK / DAI / DKK / DOP / EGP / ETH / EUR / GBP / GEL / GHS / GTQ / HKD / HUF / IDR / ILS / INR / IRR / JMD / JOD / JPY / KES / KRW / KWD / KZT / LKR / MAD / MUR / MWK / MXN / MYR / NGN / NIS / NOK / NZD / PAB / PEN / PHP / PKR / PLN / PYG / QAR / RON / RSD / RUB / RWF / SAR / SEK / SGD / THB / TRY / TUSD / TWD / TZS / UAH / UGX / USDC / USDT / UYU / VES / VND / XAF / XOF / ZAR / ZMW.


Support for over 40 exchanges (contact me to request more): 

balancer / binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmex / bitmex_u20 / bitmex_z20 / bitpanda / bitso / bitstamp / bittrex / bittrex_global / braziliex / btcmarkets / btcturk / buyucoin / cex / coinbase / coinex / cryptofresh / curve / curve_compound / curve_iearn / ethfinex / gateio / gemini / hitbtc / hotbit / huobi / idex / korbit / kraken / kucoin / liquid / livecoin / localbitcoins / luno / okcoin / okex / poloniex / southxchange / uniswap / uniswap-v2 / upbit / wazirx / zebpay.


Nearly Unlimited Assets Supported (whatever assets exist on supported exchanges).


Ethereum ICO subtoken support (pre-exchange listing) has been built in (values are static ICO values in ETH).
 
 
 
 
                    // UPPERCASE_COIN_ABRV_HERE
                    'UPPERCASE_COIN_ABRV_HERE' => array(
                        
                        'asset_name' => 'COIN_NAME_HERE',
                        'marketcap_website_slug' => 'WEBSITE_SLUG_HERE', // Website slug (URL data) on coinmarketcap / coingecko, leave blank if not listed there
                        'market_pairing' => array(
                                    
                                    // MARKET IDS / PAIRING NAMES ARE CASE-SENSITIVE!
                                    
                                    'lowercase_pairing_abrv' => array(
                                          'lowercase_exchange1' => 'MARKETIDHERE',
                                          'lowercase_exchange2' => 'pairing/COINSYMBOLHERE',
                                          'lowercase_exchange3' => 'PAIRING-COINSYMBOLHERE',
                                                    ),
                                                    
                                    'eth' => array(
                                          'lowercase_exchange1' => 'MARKETIDHERE',
                                          'lowercase_exchange2' => 'eth/COINSYMBOLHERE',
                                          'lowercase_exchange3' => 'ETH-COINSYMBOLHERE',
                                          'uniswap' => 'ETH_ETH-COINSYMBOLHERE',
                                          'uniswap-v2' => 'WETH_WETH-COINSYMBOLHERE',
                                          'balancer' => 'WETH_WETH-COINSYMBOLHERE',
                                          'bancor' => 'ETH_ETH-COINSYMBOLHERE',
                                          'curve' => 'ETH_ETH-COINSYMBOLHERE',
                                          'curve_iearn' => 'yETH_ETH-COINSYMBOLHERE',
                                          'curve_compound' => 'cETH_ETH-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'ETHSUBTOKENNAME', // Must be defined in $app_config['power_user']['ethereum_subtoken_ico_values'] in config.php
                                                    ),
                                                    
                                          ) // market_pairing END
                        
                    ), // Asset END
                    
      
 
    
 // SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

################################################################################################################


See /DOCUMENTATION-ETC/HELP-FAQ.txt for additional tips / troubleshooting FAQs.








