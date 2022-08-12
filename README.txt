
############################################################################################################################

Open Crypto Tracker - Developed by Michael Kilday <mike@dragonfrugal.com> (Copyright 2014-2022 GPLv3)


100% FREE / open source / PRIVATE cryptocurrency portfolio tracker. Email / text / Alexa / Telegram price alerts, price charts,  mining calculators, leverage / gain / loss / balance stats, news feeds + more. Privately track Bitcoin / Ethereum / unlimited cryptocurrencies. Customize as many assets / markets / alerts / charts as you want. 


Web server setup / install (for Server Edition) is available for $30 hourly if needed (see 'Manual Install' section for managed hosting, or try the auto-install bash script for self-hosted). PM me on Twitter / Skype @ taoteh1221, or get a hold of me using the below-listed contact methods.


Project Website: https://taoteh1221.github.io

LIVE PUBLIC DEMO: https://dragonfrugal.com/coin-prices

Download Latest Version: https://github.com/taoteh1221/Open_Crypto_Tracker/releases

Issue Reporting (Features / Issues / Help): https://github.com/taoteh1221/Open_Crypto_Tracker/issues

Discord: https://discord.gg/WZVK2nm

Telegram: https://t.me/joinchat/Oo2XZRS2HsOXSMGejgSO0A

Twitter: https://twitter.com/taoteh1221

Private Contact: https://dragonfrugal.com/contact


Donations support further development... 

Bitcoin:  3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW

Ethereum:  0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8

Helium:  13xs559435FGkh39qD9kXasaAnB8JRF8KowqPeUmKHWU46VYG1h

Solana:  GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU

Github Sponsors:  https://github.com/sponsors/taoteh1221

Patreon:   https://www.patreon.com/dragonfrugal

PayPal:    https://www.paypal.me/dragonfrugal


############################################################################################################################


FEATURES

-ALWAYS will remain 100% FREE F.O.S.S. software (no need to register and pay premium access fees).

-Optimized for low power devices (raspberrypi, pine64, etc).

-Desktop / Server Editions available (see README.txt for differences / usage documentation)

-Automated and user-friendly installation / upgrade script for Debian device (Ubuntu / DietPi / RaspberryPi / Armbian / Etc) app setup (server edition), on your home / internal network or website.

-PRIVATELY connects DIRECTLY to APIs / servers for data and comms (no "phoning home" to a middleman server), with optional proxy support for API connections.

-Option to use proxies for external API requests, and SMTP authentication for email sending.

-Secure HTTPS (SSL), username / password protection, and privacy mode in the portfolio interface, for privacy and security.

-Support for over 40 exchanges (including DeFi), and over 80 market pairs (country fiat currency or secondary crypto).

-Price change alerts by email / text / Alexa / Telegram (configurable alert parameters available).

-Add / edit / delete your own portfolio assets list, with your favorite exchanges / market pairs.

-Add / edit / delete your own price alerts and price charts for assets / exchanges / market pairs (supports multiple exchanges / market pairs per asset).

-Import / export your portfolio in CSV (spreadsheet) file format.

-Cryptocurrency portfolio subtotal summaries, and total portfolio worth (in crypto and your local primary currency), including value gain / loss data (with tracking support for long / short margin leverages), portfolio balance data, and marketcap data.

-A news page with over 70 different cryptocurrency-related RSS feeds to select from, including company and organization blogs / news sites / podcasts / youtube channels / reddit and stackexchange forums, with ability to add / remove feeds in the app configuration.

-Newly added news feed posts, chart data backup archives, and app error / debugging logs sent to your email.

-External resources page, includes links to marketcap stats sites / news sites / wallets / exchanges / block explorers / developer resources / newsletters / podcasts / social media / etc.

-Detailed price charts (base values in crypto and your local primary currency), with spot price / 24 hour volume, zooming, and crosshair hovering.

-Detailed portfolio statistics, including Asset Performance side-by-side comparision charts with user-defined time periods, and Portfolio Balance pie charts to get a better feel for your asset allocations.

-Switch between light / dark (night mode) theme colors.

-Crypto tools (QR code generator, altcoin trade preview / marketcap calculator in BTC and your local primary currency, etc).

-Mining calculators, to determine coin mining profitability (in crypto and your local primary currency, includes electricity costs and pool fees).

-Help page in easy-to-use FAQ format, for common issues (with support / contact links if you need additional assistance).

-System stats / alerts in the interface, by text or email or other comms, and in the logs (uptime / load averages / temperature / free disk space / used system memory / portfolio cookies and cache sizes, if available on your device), to help keep your Raspberry Pi or other device running within healthy parameters.

-System / configuration checking, alerting, logging, and auto-correcting (where possible).

-Detailed error logging and debugging (with adjustable verbosity / debug modes), to assist with troubleshooting / installation / configuration of the app.

-Plugin system, to use built-in / your own custom plugins for additional features.

-Internal restful API built-in, to allow other external apps to query real-time market data in over 80 country fiat currencies / secondary crypto pairs (raw data also available).

-Admin interface, to allow easily viewing / changing the app configuration (alerts / charts / markets / API / backups / logs / etc).

-Secure webhook capability, allowing other external apps to communicate in real-time safely (separate keys per service, without giving away the master webhook key).


############################################################################################################################


INSTALLATION AND SETUP

IMPORTANT NOTES: YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA), #WHEN YOU FIRST RUN THIS APP#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#, ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#.

Recommended MINIMUM system specs: 1 Gigahertz CPU / 512 Megabytes RAM / HIGH QUALITY 32 Gigabyte MicroSD card (running Nginx or Apache headless with PHP v7.2+)

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Setting Up The 'Desktop Edition' (runs very easily, like any other normally-downloaded native app)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To install as a normal native app on your laptop / desktop, download the "Desktop Edition" of the app here:

https://github.com/taoteh1221/Open_Crypto_Tracker/releases

After downloading, unzip the contents of the download to your desktop or other preferred file location (it doesn't matter, put it wherever you want to). Now use your operating system's file browser to enter the app's main directory, and click on "RUN_CRYPTO_TRACKER" to launch the app. TO USE PRICE CHARTS AND PRICE ALERTS TO EMAIL / TEXT / ALEXA / TELEGRAM, YOU #MUST# LEAVE THE APP RUNNING UNLESS YOU MANUALLY SETUP A CRON JOB! (see: "Setting Up Price Charts And Email / Text / Telegram / Alexa Price Alerts")

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Automatic Setup For 'Server Edition' On Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian, On Home / Internal Network (recommended way to PRIVATELY / CHEAPLY use this app)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To install / upgrade everything automatically on Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian, copy => paste => run the command below in a terminal program (using the 'Terminal' app in the system menu, or over remote SSH), while logged in AS THE USER THAT WILL RUN THE APP (user must have sudo privileges):

wget --no-cache -O FOLIO-INSTALL.bash https://git.io/JoDFD;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash

Follow the prompts. This automated script gives you the options to: install / uninstall a PHP web server automatically, download / install / configure / uninstall the latest version of the Open Crypto Tracker app automatically, setup a cron job automatically (for price alerts / price charts), and setup SSH (to update / install web site files remotely to the web server via SFTP) automatically. 

When the auto-install is completed, it will display addresses / logins to access the app (write these down / save them for future use).

SEE /DOCUMENTATION-ETC/RASPBERRY-PI/ for additional information on securing and setting up Raspberry Pi OS (disabling bluetooth, firewall setup, remote login, hostname, etc).

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Manual Installation For 'Server Edition':
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Just upload this app's files to your PHP-based web server (with an FTP client like FileZilla) and you should be all set, unless your host is a strict setup related to file writing permissions, in which case the 'cache' directory permissions should be set to '770' chmod on unix / linux systems (or 'readable / writable' on windows systems). 

Your web host must have CURL modules activated on your HTTP server. Most web hosting companies provide this "out-of-the-box" already. This app will detect whether or not CURL is setup on your website server (and also alert you to any other missing required system components / configurations). 

WINDOWS 10 USERS WHO ARE USING XAMPP WILL NEED TO ENABLE GD FOR PHP (FOR THE ADMIN LOGIN CAPTCHA SECURITY) BEFORE USING THIS APP. PLEASE SEE THE SCREENSHOT LOCATED AT /DOCUMENTATION-ETC/XAMPP-ENABLE-GD.png FOR A VISUAL ON SETTING THIS UP EASILY.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Setting up a cron job for charts and price alerts by email / mobile phone text / Alexa / Telegram notifications 
(get notifications sent to you, even when your PC / Laptop is offline)...
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IMPORTANT NOTES: THIS IS FOR 'SERVER EDITION' ONLY, UNLESS YOU DISABLE 'desktop_cron_interval' IN THE POWER USER CONFIG IN THE 'DESKTOP EDITION'...IN WHICH CASE READ THE MANUAL CRON JOB INSTALL SECTIONS BELOW THAT ARE RELEVANT TO YOUR OPERATING SYSTEM.

You can setup price charts or price alerts in your app install. Price alerts can be sent to email, mobile phone text, Telegram, and Alexa notifications. You will be alerted when the [configured default primary currency] price of an asset goes up or down a certain percent or more (whatever percent you choose in the settings), for specific exchange / base pair combinations for that asset. You can even setup alerts and charts for multiple exchanges / base pairs for the same asset.
	    
Running price charts or price alerts requires setting up a cron job or scheduled task on the Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian / Windows 10 machine or website server (this is automated for Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian users using the automated FOLIO-INSTALL.bash script / Windows 10 users who run the ADD-WIN10-SCHEDULER-JOB.bat file), otherwise charts / alerts will not work. Also see the related settings in Admin Config for charts / alerts. 

Once a cron job is setup, there is no need to keep your PC / Laptop turned on. The price charts and price alerts run automatically from your Open Crypto Tracker app server installation. If you encounter errors or the charts / alerts don't work during setup, check the error logs file at /cache/logs/error.log for errors in your configuration setup. Basic checks are performed and errors are reported there, and on the Settings page. 

If you want to turn on these cron job based features and more (chart data backups, new RSS news feed entries emails, error log emails / etc), then the file cron.php (located in the primary directory of this app) must be setup as a cron job on your Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian / Windows 10 / website server device. 

As mentioned previously, if you run the automated setup / install script for Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian / Windows 10 devices on home / internal networks, automatic cron job setup is offered as an option during this process. If you are using a full stack website host for hosting a TLD website domain name remotely, consult your web server host's documentation or help desk for their particular method of setting up a cron job.

Note that you should have the cron job run every 5, 10, 15, 20, or 30 minutes 24/7, based on how often you want chart data points / alerts / any other cron based features to run. Setting up the cron job to run every 20 minutes is the RECOMMENDED lowest time interval. IF SET BELOW 20 MINUTES, light chart disk writes may be excessive for lower end hardware (Raspberry PI MicroSD cards etc). IF SET #VERY LOW# (5 / 10 minutes), the free exchange APIs may throttle / block your data requests temporarily on occasion for requesting data too frequently (negatively affecting your alerts / charts). 

FOR WINDOWS 10 USERS, just click / run the file 'ADD-WIN10-SCHEDULER-JOB.bat' found in the main directory of the app, follow the prompts, and everything will be automatically setup for you. As long as you login into your Windows account after system startup, the scheduled task will run until your computer is shut off OR you logout of that user account.

FOR LINUX / MAC USERS, here is an example cron job command line for reference below (NOT including any cron parameters a remote host interface may require), to setup as the "command" within a cron job. Replace system paths in the example with the correct ones for your server (TIP - A very common path to PHP on a server is /usr/bin/php):

/path/to/php -q /home/username/path/to/website/this_app/cron.php

Here is another example of a COMPLETE cron command that can be added by creating the following file (you'll need sudo/root permissions): /etc/cron.d/cryptocoin on a linux-based machine with systemd (to run every 20 minutes 24/7)...play it safe and add a newline after it as well if you install examples like these:

*/20 * * * * WEBSITE_USERNAME_GOES_HERE /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1

If your system DOES NOT have the directory /etc/cron.d/ on it, then NEARLY the same format (minus the username) can be installed via the legacy 'crontab -e' command (YOU MUST BE logged in as the user you want running the cron job):

*/20 * * * * /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1


IMPORTANT CRON JOB NOTES: 

MAKE SURE YOU ONLY USE EITHER /etc/cron.d/, or 'crontab -e', NOT BOTH...ANY OLD DUPLICATE CRONTAB ENTRIES WILL RUN YOUR CRON JOB TOO OFTEN. If everything is setup properly, and the cron job still does NOT run, your particular server may require the cron.php file permissions to be set as 'executable' ('750' chmod on unix / linux systems) to allow running it.


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Using the built-in (internal) REST API:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This app has a built-in (internal) REST API available, so other external apps can connect to it and receive market data, including market conversion (converting the market values to their equivalent value in country fiat currencies and secondary cryptocurrency market pairs).

To see a list of the supported assets in the API, use the endpoint: "/api/asset_list"

To see a list of the supported exchanges in the API, use the endpoint: "/api/exchange_list"

To see a list of the supported markets for a particular exchange in the API, use the endpoint: "/api/market_list/[exchange name]"

To see a list of the supported conversion currencies (market values converted to these currency values) in the API, use the endpoint: "/api/conversion_list"

To get raw market values AND also get a market conversion to a supported conversion currency (see ALL requested market values also converted to values in this currency) in the API, use the endpoint: "/api/market_conversion/[conversion currency]/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]"

To skip conversions and just receive raw market values in the API, you can use the endpoint: "/api/market_conversion/market_only/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]"

For security, the API requires a key / token to access it. This key must be named "api_key", and must be sent with the "POST" data method.


// SEE /DOCUMENTATION-ETC/REST-API-EXAMPLES.txt FOR EXAMPLES OF CALLING THE API WITH CURL, JAVASCRIPT, AND PHP


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adding / editing / deleting assets and markets in the portfolio assets:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IMPORTANT NOTE: IN THE v6 RELEASE (Coming Soon™), DOING THIS MANUALLY IN A TEXT EDITOR WON'T BE NECESSARY. YOU WILL BE ABLE TO DO IT IN THE "Admin Config => Portfolio Assets" INTERFACE MUCH EASIER.

Below is an example for editing your assets / markets into the portfolio assets in the file config.php (located in the primary directory of this app), in the PORTFOLIO ASSETS section. It's very quick / easy to do (after you get the hang of it, lol). Also see the text file /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt, for a pre-configured set of default settings and example assets / markets. 

Contact any supported exchange's help desk if you are unaware of the correct formatting of the trading pair naming you are adding in the configuration file (examples: Kraken has arbitrary Xs inserted in SOME older pair names, HitBTC sometimes has tether pair without the "T" in the symbol name).


Support for over 80 trading pairs (country fiat currency or secondary crypto, contact me to request more): 

AED / ARS / AUD / BAM / BDT / BOB / BRL / BTC / BWP / BYN / CAD / CHF / CLP / CNY / COP / CRC / CZK / DAI / DKK / DOP / EGP / ETH / EUR / GBP / GEL / GHS / GTQ / HKD / HUF / IDR / ILS / INR / IRR / JMD / JOD / JPY / KES / KRW / KWD / KZT / LKR / MAD / MKR / MUR / MWK / MXN / MYR / NGN / NIS / NOK / NZD / PAB / PEN / PHP / PKR / PLN / PYG / QAR / RON / RSD / RUB / RWF / SAMO / SAR / SEK / SGD / SLC / SOL / THB / TRY / TUSD / TWD / TZS / UAH / UGX / UNI / USDC / USDT / UYU / VES / VND / XAF / XOF / ZAR / ZMW


Support for over 40 exchanges (contact me to request more): 

binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmart / bitmex / bitmex_u20 / bitmex_z20 / bitpanda / bitso / bitstamp / bittrex / bittrex_global / btcmarkets / btcturk / buyucoin / bybit / cex / coinbase / coindcx / coinex / coinspot / crypto.com / ethfinex / ftx / ftx_us / gateio / gemini / coingecko_btc / coingecko_eth / coingecko_eur / coingecko_gbp / coingecko_usd / hitbtc / hotbit / huobi / jupiter_ag / korbit / kraken / kucoin / liquid / localbitcoins / loopring / loopring_amm / luno / okcoin / okex / poloniex / southxchange / unocoin / upbit / wazirx / zebpay


Nearly Unlimited Assets Supported (whatever assets exist on supported exchanges).


Ethereum ICO subtoken support (pre-exchange listing) has been built in (values are static ICO values in ETH).


USAGE (ADDING / UPDATING COINS):
 
 
 
                    // UPPERCASE_COIN_ABRV_HERE
                    'UPPERCASE_COIN_ABRV_HERE' => array(
                        
                        'name' => 'COIN_NAME_HERE',
                        // Website slug (URL data) on coinmarketcap / coingecko, leave blank if not listed there
                        'mcap_slug' => 'WEBSITE_SLUG_HERE', 
                        // MARKET IDS ARE CASE-SENSITIVE!
                        'pair' => array(
                                    
                                    
                        		'lowercase_pair_abrv' => array(
                                          'lowercase_exchange1' => 'MARKETIDHERE',
                                          'lowercase_exchange2' => 'ASSET/PAIR',
                                          'lowercase_exchange3' => 'ASSET-PAIR',
                                          'lowercase_exchange4' => 'ASSET_PAIR',
                                          'lowercase_exchange5' => 'ASSETPAIR',
                                          // GENERIC PAIR PRICE (IF NO EXHANGE APIs AVAILABLE)
                                          // USE COINGECKO'S API ID FOR THIS ASSET (SEE COINGECKO ASSET PAGE'S INFO SECTION) 
                                          // LOWERCASE_PAIR_ABRV MUST BE SUPPORTED BY COINGECKO'S 'vs_currencies' API PARAMETER!
                                          'coingecko_LOWERCASE_PAIR_ABRV' => 'coingecko_api_id_here',
                                          ),
                                                    
                                                    
                          		'eth' => array(
                                          'lowercase_exchange1' => 'MARKETIDHERE',
                                          'lowercase_exchange2' => 'ASSET/ETH',
                                          'lowercase_exchange3' => 'ASSET-ETH',
                                          'lowercase_exchange4' => 'ASSET_ETH',
                                          'lowercase_exchange5' => 'ASSETETH',
                                          // ETH ICOs...ETHSUBTOKENNAME MUST be defined in 'eth_erc20_icos' (Admin Config POWER USER section)
                                          'ico_erc20_value' => 'ETHSUBTOKENNAME', 
                                          // GENERIC ETH PRICE (IF NO EXHANGE APIs AVAILABLE)
                                          // USE COINGECKO'S API ID FOR THIS ASSET (SEE COINGECKO ASSET PAGE'S INFO SECTION) 
                                          'coingecko_eth' => 'coingecko_api_id_here',
                                          ),

                                                    
                                'btc' => array(
                                          // GENERIC BTC PRICE (IF NO EXHANGE APIs AVAILABLE)
                                          // USE COINGECKO'S API ID FOR THIS ASSET (SEE COINGECKO ASSET PAGE'S INFO SECTION) 
                                          'coingecko_btc' => 'coingecko_api_id_here',
                                          ),

                                                    
                                'usd' => array(
                                          // GENERIC USD PRICE (IF NO EXHANGE APIs AVAILABLE)
                                          // USE COINGECKO'S API ID FOR THIS ASSET (SEE COINGECKO ASSET PAGE'S INFO SECTION) 
                                          'coingecko_usd' => 'coingecko_api_id_here',
                                          ),

                                                    
                                'eur' => array(
                                          // GENERIC EUR PRICE (IF NO EXHANGE APIs AVAILABLE)
                                          // USE COINGECKO'S API ID FOR THIS ASSET (SEE COINGECKO ASSET PAGE'S INFO SECTION) 
                                          'coingecko_eur' => 'coingecko_api_id_here',
                                          ),

                                                    
                                'gbp' => array(
                                          // GENERIC GBP PRICE (IF NO EXHANGE APIs AVAILABLE)
                                          // USE COINGECKO'S API ID FOR THIS ASSET (SEE COINGECKO ASSET PAGE'S INFO SECTION) 
                                          'coingecko_gbp' => 'coingecko_api_id_here',
                                          ),

                                                    
                       	) // pair END
                        
                    ), // Asset END
                    
      
 
    
 // SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

################################################################################################################


SEE /DOCUMENTATION-ETC/PLUGINS-README.txt for creating your own custom plugins (custom code that adds additional features)


See TROUBLESHOOTING.txt for additional tips / troubleshooting FAQs.








