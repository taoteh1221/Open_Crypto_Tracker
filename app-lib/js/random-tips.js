
// Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


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
	    	quote:"<i><u>*ALWAYS*</u></i> <a href='https://www.google.com/search?q=financial+advisors+near+me' target='_blank'>CONSULT A FINIANCIAL ADVISOR</a>, IF YOU ARE UNAWARE OF WHAT RISKS ARE PRESENT, *AND* YOU ARE INVESTING *SIGNIFICANT* AMOUNTS OF MONEY!",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>REMEMBER</i>, the <a href='https://www.google.com/search?q=barbell+portfolio+strategy' target='_blank'>Barbell Portfolio Strategy</a> works VERY WELL for MANY investors that use it!",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i><u>ALWAYS AVOID</u></i> copycat coins (that copy / mimick already-popular networks BUT HAVE NO SIGNIFICANT FEATURE IMPROVEMENTS), coins with high inflation rates (creating too many new coins infinitely), coins that are NOT on a fully decentralized network (small groups control everything), and coins with very little on-chain transaction activity (indicating low <i>REAL WORLD</i> user adoption).",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>WATCH OUT FOR</i> <a href='https://www.google.com/search?q=pig+butchering+scams' target='_BLANK'>\"Pig Butchering\"</a> / <a href='https://www.google.com/search?q=catfishing+scams' target='_BLANK'>\"Catfishing\"</a> scams, and <i>NEVER</i> tell <i>ANYBODY</i> about your investment portfolio details. You would be surprised at how many people will take advantage of others for money. 😮 <i>KEEP THIS INFORMATION PRIVATE</i>!",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>FOOD FOR THOUGHT:</i> <i>NEARLY <u>ALL</u></i> crypto tokens that are either 'liquid staking', 'wrapped', 'bridged' (*WITHOUT* 'burn-and-mint' bridging security), or are a 'stable coin', ARE ONLY EQUIVELENT TO '<a href='https://www.google.com/search?q=ious+meaning+in+finance' target='_blank'>IOUs</a>', TO SWAP LATER FOR THE *REAL* UNDERLYING ASSET(S). So THINK TWICE before putting more than a relatively small percentage of your NET worth in these types of tokens, as they are higher risk than holding the underlying asset(s) they are pegged to.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"For privacy / security, ALWAYS round your STORED portfolio balances to as few decimal points as is feasible (AND preferably buy / transfer cryptocurrency in nice rounded amounts too). Then hackers / snoopers cannot as easily guess and potentially follow your transaction history in block explorers online.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"ONLY HIGHLY EXPERIENCED DAY TRADERS should attempt timing short term trade entry points (buying, to sell in a few days / weeks at a higher price). The average investor IS MUCH BETTER OFF HOLDING LONG TERM, and " + bitcoin_primary_currency_pair + "-cost averaging your cryptocurrency purchases / buys on a weekly or monthly basis (buying the same amount at regular intervals). Remember, " + bitcoin_primary_currency_pair + "-cost averaging DOES #NOT# WORK for <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"If you insist on buying LONG SHOT (#VERY# high risk) SMALL marketcap or NEWER assets (requiring #A TON# OF DILIGENCE / PATIENCE), *HIGHLY* consider getting #NO MORE THAN# a 'moon bag' worth (#NO MORE THAN# between 1% and 5% of your portfolio PER-ASSET, AND A TOTAL OF #NO MORE THAN# 10% of your portfolio). If it goes down 50% in value and keeps going down, sell it and you don't lose much. If it goes up between 200% and 500% in value (3x to 6x original value) or higher, REBALANCE it to not be more than between 1% and 10% of your portfolio again (by selling a significant portion of it). CAREFULLY TRACK YOUR SUCCESS RATE. If you are no good at picking long shots, stick to the <i>largest AND oldest marketcaps / HIGHEST ON-CHAIN ACTIVITY</i> assets instead.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). <i>NEVER</i> put more than ~5% of your total investment worth into ALL your leverage trades COMBINED, or you will <u>RISK LOSING EVERYTHING</u>!",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"Speculating on popular \"meme coins\" (dog / cat / ape / frog coins, etc) is <u>EXTREMELY RISKY</u>. <i>NEVER</i> put more than ~5% of your total investment worth into ALL your meme coin assets COMBINED (AKA small \"moon bags\"), or you will <u>RISK LOSING EVERYTHING</u>! They are EXTREMELY VOLITILE becuase they are HEAVILY SPECULATED ON (NOT long term investments for many traders).",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>NEVER</i> invest more than you can afford to lose.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>DOLLAR-COST-AVERAGE (DCA)</i> into investments weekly OR monthly, <i>NEVER GO \"ALL-IN\"</i> with 100% of your cash / savings at once! You *WILL NOT* be able to handle the stress <i>if it goes down LONG TERM!</i>",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>ALWAYS <u>buy low</u> AND <u>sell high</u></i>. (NOT the other way around!), *UNLESS* you CAREFULLY decide you've accidentally bought an asset that will probably go nowhere in value long term, relative to other assets you are interested in.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>ALWAYS</i> diversify / balance your portfolio with <i>mostly largest AND oldest marketcaps (which are <i>relatively</i> less volatile) / HIGHEST ON-CHAIN ACTIVITY</i> assets, for you <i>and yours</i> safety and sanity.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>NEVER</i> buy an asset because of somebody's opinion of it (only buy based on <i>YOUR</i> opinion of it).",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>ALWAYS <u>fully research</u></i> your planned investment beforehand (fundamentals are just as important as long term chart TA, <i>and any short term chart TA is pure BS to be ignored</i>).",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>LITERALLY nearly 99.9% of all tokens (including NFTs)</i> listed on Coinmarketcap.com (OR any other site) are either scams, garbage, or dead ends. Tread VERY carefully when investing, and RESEARCH AT LENGTH BEFOREHAND (that does NOT include *BLINDLY* believing some CEO / founder / influencer sweet talking their own token, telling you how competing systems suck and their system is better, or explaining how them owning over 50% of the total coin supply is not out of greed). 😮",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"<i>Hang on tight</i> until you can't stand fully holding anymore / want to or must make a position exit percentage <i><u>OFFICIAL</u></i>. (YOU HAVEN'T 'LOST' <i><u>OR</u></i> 'MADE' <i><u>ANYTHING</u></i> UNTIL YOU SELL A PERCENTAGE OF IT!)",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"Be careful out there in this cryptoland frontier <i>full of dead-end coins, garbage coins, scam coins, and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues (wolves in sheep's clothing)! 😮",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"PATIENCE is key to trading. When you think an asset will rise in value, think in YEARS #NOT# DAYS. No matter who says it, guessing market values in days or weeks is a fools game (throwing darts at a bullseye blind-folded).",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"Coins that are mined into creation (via Proof-of-Work or Proof-of-Stake) are commodities from a LEGAL standpoint. Coins that are created without cost by network operators for sale to private OR public investors are securities from a LEGAL standpoint. A securities-class token MAY OVER TIME be deemed a commodities-class token by authorities of jusidictions, IF IT HAS ENOUGH UTILITY ON A FULLY FUNCTIONAL / ACTIVELY USED NETWORK. It's VERY IMPORTANT to know which class of tokens you are buying, becuase securities-class tokens are #HEAVILY REGULATED# and may NEVER get listed on highly-regulated exchanges.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"Store your coins in your own wallet (<i>hardware wallets allow this safely and easily</i>), do NOT store large holdings on an exchange. If an exchange is hacked OR mishandles your coins, and your coins are stored on that exchange, <u>you easily can loose your coins with no recourse</u>. 😮 Also, ALWAYS enable 2-factor-authentication ('2FA' with Google Authenticator, Authy, etc, <i>preferably</i> on a device with no sim card / phone number) in your exchange account for logins / withdrawals, and NEVER USE 2FA VIA MOBILE TEXT MESSAGE, OR YOU COULD GET SIM-SWAP HACKED AND LOOSE ALL YOUR COINS!",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"A good cryptocoin ages like fine wine, while a <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> ages like milk.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"The biggest investment you will EVER make (and NEVER regret) is ALWAYS value your health and happiness more than ALL THE MONEY IN THE WORLD. NEVER let chasing after ANY AMOUNT of wealth take that from you, EVER.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"Investing versus speculating: One is a LONG TERM commitment you believe has high quality fundamentals that will preserve and grow value, and the other is a SHORT TERM commitment you believe is currently lacking accurate price discovery. For each asset you buy, it's important to CONSCIOUSLY know whether you are investing, or merely speculating.",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"The \"grass on the other side looks greener\" MORE OFTEN THAN NOT will always be in your head, when you see other assets performing better than the ones you currently are holding. That's why it's SO IMPORTANT TO DO YOUR REASEARCH FULLY! If you KNOW you didn't buy garbage, AND you didn't over-extend your position (you CAN afford to hold it for a few years without selling any), just RELAX AND DON'T BE GREEDY...otherwise you are living for money (not ENJOYING life itself) AND YOU WILL END UP FLIPPING YOURSELF OUT, WHICH IS A CRAPPY WAY TO LIVE!",
	    	name:"taoteh1221 (lead dev of this app)"
	    },
	    {
	    	quote:"ALWAYS have a future plan in place, of what you will buy / sell: 1) Around a certain future date in time. 2) If a certain price target has been met or exceeded. This doesn't need to be \"all in\" or \"all out\". For instance, you may want to split your capital gains between 2 tax years within a tight time period, in late December / early the following January, to avoid higher tax brackets.",
	    	name:"taoteh1221 (lead dev of this app)"
	    }

	];
		