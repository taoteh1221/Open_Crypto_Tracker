
// Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


// https://codepen.io/kkoutoup/pen/zxmGLE
var quoteSource=[
		{
			quote: "Whether we're talking about socks or stocks, I like buying quality merchandise when it is marked down.",
			name:"Warren Buffett"
	    },
	    {
	    	quote:"Widespread fear is your friend as an investor because it serves up bargain purchases.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"If you aren't willing to own a stock for ten years, don't even think about owning it for ten minutes.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"Buy a stock the way you would buy a house. Understand and like it such that you'd be content to own it in the absence of any market.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"Should you find yourself in a chronically leaking boat, energy devoted to changing vessels is likely to be a more productive than energy devoted to patching leaks.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"Half of all coin-flippers will win their first toss; none of those winners has an expectation of profit if he continues to play the game.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"Rule #1: Don’t lose money. Rule #2: Don’t forget Rule #1.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"The stock market is a device for transferring money from the impatient to the patient.",
	    	name:"Warren Buffett"
	    },
	    {
	    	quote:"Bitcoin frees people from trying to operate in a modern market economy.",
	    	name:"Tim Draper"
	    },
	    {
	    	quote:"I don’t have to pay those people who wear these beautiful suits coming out of big fancy banks to hold my money for me. I can hold my money right here. I don’t have to watch all that money disappear into these big, beautiful buildings and these people with fancy suits. I’ve got my money right here on a Ledger. That is a really powerful feeling.",
	    	name:"Tim Draper"
	    },
	    {
	    	quote:"Failure hurts, but failing is a part of the free market.",
	    	name:"Tim Draper"
	    },
	    {
	    	quote:"Know what you own, and know why you own it.",
	    	name:"Peter Lynch"
	    },
	    {
	    	quote:"Investing should be more like watching paint dry or watching grass grow. If you want excitement, take $800 and go to Las Vegas.",
	    	name:"Paul Samuelson"
	    },
	    {
	    	quote:"The secret to investing is to figure out the value of something – and then pay a lot less.",
	    	name:"Joel Greenblatt"
	    },
	    {
	    	quote:"The entrance strategy is actually more important than the exit strategy.",
	    	name:"Edward Lampert"
	    },
	    {
	    	quote:"Invest for the long-term.",
	    	name:"Lou Simpson"
	    },
	    {
	    	quote:"For privacy / security, ALWAYS round your STORED portfolio balances to as few decimal points as is feasible (AND preferably buy / transfer cryptocurrency in nice rounded amounts too). Then hackers / snoopers cannot as easily guess and potentially follow your transaction history in block explorers online.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"ONLY HIGHLY EXPERIENCED DAY TRADERS should attempt timing short term trade entry points (buying, to sell in a few days / weeks at a higher price). The average investor IS MUCH BETTER OFF HOLDING LONG TERM, and " + window.btc_primary_currency_pairing + "-cost averaging your cryptocurrency purchases / buys on a weekly or monthly basis (buying the same amount at regular intervals). Remember, " + window.btc_primary_currency_pairing + "-cost averaging DOES #NOT# WORK for <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"For longer shot (#VERY# high risk) small marketcap assets, HIGHLY consider getting #NO MORE THAN# a 'moon bag' worth (#NO MORE THAN# between 1% and 5% of your portfolio PER-ASSET, AND A TOTAL OF #NO MORE THAN# 10% of your portfolio). If it goes down 50% and keeps going down, sell it and you don't lose much. If it goes up between 400% and 800% (4x to 8x original value) or higher, REBALANCE it to not be more than between 1% and 10% of your portfolio again (by selling a significant portion of it). CAREFULLY TRACK YOUR SUCCESS RATE. If you are no good at picking long shots, stick to the largest and oldest marketcaps instead.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). <i>NEVER</i> put more than ~5% of your total investment worth into leverage trades, or you will <u>RISK LOSING EVERYTHING</u>!",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>NEVER</i> invest more than you can afford to lose.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>ALWAYS <u>buy low</u> AND <u>sell high</u></i>. (NOT the other way around!)",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>ALWAYS</i> diversify / balance your portfolio with <i>mostly largest AND oldest marketcap</i> assets (which are <i>relatively</i> less volatile), for you <i>and yours</i> safety and sanity.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>NEVER</i> buy an asset because of somebody's opinion of it (only buy based on <i>YOUR</i> opinion of it).",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>ALWAYS <u>fully research</u></i> your planned investment beforehand (fundamentals are just as important as long term chart TA, <i>and any short term chart TA is pure BS to be ignored</i>).",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>LITERALLY nearly 99% of all tokens</i> listed on Coinmarketcap.com (OR any other site) are either scams, garbage, or dead ends. Tread VERY carefully when investing, and RESEARCH AT LENGTH BEFOREHAND (that does NOT included listening to some CEO / founder / influencer sweet talk their own token, tell you how competing systems suck and their system is better, or explain how them owning over 50% of the total coin supply is not out of greed). 😮",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"<i>Hang on tight</i> until you can't stand fully holding anymore / want to or must make a position exit percentage <i><u>OFFICIAL</u></i>. (YOU HAVEN'T 'LOST' <i><u>OR</u></i> 'MADE' <i><u>ANYTHING</u></i> UNTIL YOU SELL A PERCENTAGE OF IT!)",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"Be careful out there in this cryptoland frontier <i>full of garbage coins, scam coins, and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues (wolves in sheep's clothing)! 😮",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"PATIENCE is key to trading. When you think an asset will rise in value, think in YEARS #NOT# DAYS. No matter who says it, guessing market values in days or weeks is a fools game (throwing darts at a bullseye blind-folded).",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"Coins that are mined into creation (via Proof-of-Work or Proof-of-Stake) are commodities from a LEGAL standpoint. Coins that are created without cost by network operators for sale to private OR public investors are securities from a LEGAL standpoint. A securities-class token MAY OVER TIME be deemed a commodities-class token by authorities of jusidictions, IF IT HAS ENOUGH UTILITY ON A FULLY FUNCTIONAL / ACTIVELY USED NETWORK. It's VERY IMPORTANT to know which class of tokens you are buying, becuase securities-class tokens are #HEAVILY REGULATED# and may NEVER get listed on highly-regulated exchanges.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"Coins that are mined into creation (via Proof-of-Work or Proof-of-Stake) are commodities from a LEGAL standpoint. Coins that are created without cost by network operators for sale to private OR public investors are securities from a LEGAL standpoint. A securities-class token MAY OVER TIME be deemed a commodities-class token by authorities of jusidictions, IF IT HAS ENOUGH UTILITY ON A FULLY FUNCTIONAL / ACTIVELY USED NETWORK. It's VERY IMPORTANT to know which class of tokens you are buying, becuase securities-class tokens are #HEAVILY REGULATED# and may NEVER get listed on highly-regulated exchanges.",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"Store your coins in your own wallet (<i>hardware wallets allow this safely and easily</i>), do NOT store large holdings on an exchange. If an exchange is hacked, and your coins are stored on that exchange, <u>you easily can loose your coins with no recourse</u>. 😮 Also, ALWAYS enable 2-factor-authentication ('2FA' with Google Authenticator, Authy, etc, <i>preferably</i> on a device with no sim card / phone number) in your exchange account for logins / withdrawals, and NEVER USE 2FA VIA MOBILE TEXT MESSAGE, OR YOU COULD GET SIM-SWAP HACKED AND LOOSE ALL YOUR COINS!",
	    	name:"Mike Kilday (creator of this app)"
	    },
	    {
	    	quote:"A good cryptocoin ages like fine wine, while a <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> ages like milk.",
	    	name:"Mike Kilday (creator of this app)"
	    }

	];
		