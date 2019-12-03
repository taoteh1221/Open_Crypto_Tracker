<?php

/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 

// All supported mobile network email-to-text gateway (domain name) configurations
// Network key names MUST BE LOWERCASE (for reliability / consistency, 
// as these key names are always called from (forced) lowercase key name lookups)
$mobile_networks = array(


// [NO NETWORK (when using textbelt / textlocal API instead)]
'skip_network_name' => NULL,




// [INTERNATIONAL]
'esendex' => 'echoemail.net',
'global_star' => 'msg.globalstarusa.com',




// [MISCELLANEOUS COUNTRIES]
'beeline' => 'sms.beemail.ru',
'bluesky' => 'psms.bluesky.as',
'china_mobile' => '139.com',
'claro_ni' => 'ideasclaro-ca.com',
'claro_pr' => 'vtexto.com',
'digicel' => 'digitextdm.com',
'emtel' => 'emtelworld.net',
'guyana_tt' => 'sms.cellinkgy.com',
'helio' => 'myhelio.com',
'ice' => 'sms.ice.cr',
'm1' => 'm1.com.sg',
'mas_movil' => 'cwmovil.com',
'mobitel' => 'sms.mobitel.lk',
'movistar_ar' => 'movimensaje.com.ar',
'movistar_uy' => 'sms.movistar.com.uy',
'setar' => 'mas.aw',
'spikko' => 'SpikkoSMS.com',
'tmobile_hr' => 'sms.t-mobile.hr',
'tele2_lv' => 'sms.tele2.lv',
'tele2_se' => 'sms.tele2.se',
'telcel' => 'itelcel.com',
'vodafone_pt' => 'sms.vodafone.pt',




// [AUSTRALIA]
'sms_broadcast' => 'send.smsbroadcast.com.au',
'sms_central' => 'sms.smscentral.com.au',
'sms_pup' => 'smspup.com',
'tmobile_au' => 'optusmobile.com.au',
'telstra' => 'sms.tim.telstra.com',
'ut_box' => 'sms.utbox.net',




// [AUSTRIA]
'firmen_sms' => 'subdomain.firmensms.at',
'tmobile_at' => 'sms.t-mobile.at',




// [ARGENTINA]
'cti_movil' => 'sms.ctimovil.com.ar',
'movistar_ar' => 'sms.movistar.net.ar',
'personal' => 'alertas.personal.com.ar',




// [BRAZIL]
'claro_br' => 'clarotorpedo.com.br',
'vivo' => 'torpedoemail.com.br',




// [BULGARIA]
'globul' => 'sms.globul.bg',
'mobiltel' => 'sms.mtel.net',




// [CANADA]
'bell' => 'txt.bell.ca',
'bell_mts' => 'text.mts.net',
'fido' => 'sms.fido.ca',
'koodo' => 'msg.telus.com',
'lynx' => 'sms.lynxmobility.com',
'pc_telecom' => 'mobiletxt.ca',
'rogers' => 'mms.rogers.com',
'sasktel' => 'pcs.sasktelmobility.com',
'telus' => 'mms.telusmobility.com',
'virgin_ca' => 'vmobile.ca',
'wind' => 'txt.windmobile.ca',




// [COLUMBIA]
'claro_co' => 'iclaro.com.co',
'movistar_co' => 'movistar.com.co',
'tigo' => 'sms.tigo.com.co',




// [EUROPE]
'freebie_sms' => 'smssturen.com',
'tellus_talk' => 'esms.nu',




// [FRANCE]
'bouygues' => 'mms.bouyguestelecom.fr',
'orange_fr' => 'orange.fr',
'sfr' => 'sfr.fr',




// [GERMANY]
'e_plus' => 'smsmail.eplus.de',
'o2' => 'o2online.de',
'tmobile_de' => 't-mobile-sms.de',
'vodafone_de' => 'vodafone-sms.de',




// [HONG KONG]
'access_you' => 'messaging.accessyou.com',
'csl' => 'mgw.mmsc1.hkcsl.com',




// [ICELAND]
'vodafone_is' => 'sms.is',
'box_is' => 'box.is',




// [INDIA]
'aircel' => 'aircel.co.in',
'airtel' => 'airtelmail.com',
'airtel_ap' => 'airtelap.com',
'airtel_chennai' => 'airtelchennai.com',
'airtel_kerala' => 'airtelkerala.com',
'airtel_kk' => 'airtelkk.com',
'airtel_kolkata' => 'airtelkol.com',
'celforce' => 'celforce.com',
'escotel' => 'escotelmobile.com',
'idea' => 'ideacellular.net',
'rpg' => 'rpgmail.net',
'vodafone_in' => 'sms.vodafone.in',




// [ITALY]
'tim' => 'timnet.com',
'vodafone_it' => 'sms.vodafone.it',




// [NETHERLANDS]
'orange_nl' => 'sms.orange.nl',
'tmobile_nl' => 'gin.nl',




// [NEW ZEALAND]
'telecom' => 'etxt.co.nz',
'vodafone_nz' => 'mtxt.co.nz',




// [NORWAY]
'sendega' => 'sendega.com',
'teletopia' => 'sms.teletopiasms.no',




// [SOUTH AFRICA]
'mtn' => 'sms.co.za',
'vodacom' => 'voda.co.za',




// [SPAIN]
'esendex' => 'esendex.net',
'movistar_es' => 'movistar.net',
'vodafone_es' => 'vodafone.es',




// [Switzerland]
'box_ch' => 'mms.boxis.net',
'sunrise_ch' => 'gsm.sunrise.ch',




// [POLAND]
'orange_pl' => 'orange.pl',
'plus' => 'text.plusgsm.pl',
'polkomtel' => 'text.plusgsm.pl',




// [UNITED KINGDOM]
'media_burst' => 'sms.mediaburst.co.uk',
'orange_uk' => 'orange.net',
'tmobile_uk' => 't-mobile.uk.net',
'txt_local' => 'txtlocal.co.uk',
'uni_movil' => 'viawebsms.com',
'virgin_uk' => 'vxtras.com',
'vodafone_uk' => 'vodafone.net',




// [UNITED STATES]
'alaska_comm' => 'msg.acsalaska.com',
'att' => 'txt.att.net',
'bluegrass' => 'mms.myblueworks.com',
'boost' => 'myboostmobile.com',
'cellcom' => 'cellcom.quiktxt.com',
'chariton_valley' => 'sms.cvalley.net',
'chat_mobility' => 'mail.msgsender.com',
'clear_talk' => 'sms.cleartalk.us',
'cricket' => 'mms.mycricket.com',
'cspire' => 'cspire1.com',
'dtc' => 'sms.advantagecell.net',
'element' => 'SMS.elementmobile.net',
'gci' => 'mobile.gci.net',
'hawaiian_telcom' => 'hawaii.sprintpcs.com',
'nextech' => 'sms.ntwls.net',
'pioneer' => 'zsend.com',
'rogers' => 'mms.rogers.com',
'simple_mobile' => 'smtext.com',
'southern_linc' => 'page.southernlinc.com',
'south_central_comm' => 'rinasms.com',
'sprint' => 'messaging.sprintpcs.com',
'tmobile_us' => 'tmomail.net',
'telus' => 'mms.telusmobility.com',
'trac_fone' => 'mmst5.tracfone.com',
'union' => 'union-tel.com',
'us_cellular' => 'email.uscc.net',
'verizon' => 'vtext.com',
'viaero' => 'mmsviaero.com',
'virgin_us' => 'vmobl.com',
'west_central' => 'sms.wcc.net',
'xit' => 'sms.xit.net',


);


?>