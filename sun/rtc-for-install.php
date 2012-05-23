<?php
 $rtc = array (
  'domain' => '',
  'language' => $language,
  'sourceurl' => $host . 'source/',
  'host' => $host2 ,
  'style' => 'ppframe',
  'showtime' => '2',
  'timezone' => '8',
  'admin_host' => $host . 'admin/',
  'passport_login' => $host . 'passport/login.php',
  'passport_logout' => $host . 'passport/logout.php',
  'passport_reg' => $host . 'passport/reg.php',
  'passport_hash' => $random_str,
  'passport_ucpp' => 'PP_Auth',
  'passport_uctime' => '60',
  'passport_psmethod' => 'md5',
  'passport_table' => '##__passport',
  'passport_prikey' => 'uid',
  'passport_passkey' => 'password',
  'passport_uniqueid' => 'username',
  'passport_psckmethod' => 'ppframe',
  'passport_ucpre' => 'pp_',
  'page_pattern' => '<span class="pagelink_num"><a href="{~~pagelink~~}">{~~page~~}</a></span>',
  'page_stpattern' => '<span class="pagelink_num_nonce">{~~page~~}</span>',
  'page_fppattern' => '<span class="pagelink_pre"><a href="{~~pagelink~~}">First</a></span><span class="pagelink_pre"><a href="{~~pageup~~}">Pre</a></span>',
  'page_lppattern' => '<span class="pagelink_next"><a href="{~~pagedown~~}">Next</a></span><span class="pagelink_next"><a href="{~~pagelink~~}">Last</a></span>',
  'page_pageprepattern' => '<span class="pagelink_pre_nolink">{~~num~~}R/{~~page~~}P {~~cpage~~}</span>',
  'page_pagetailpattern' => '<sapn>
  <input name="{~~tag~~}" type="text" id="{~~tag~~}" size="4" />
  <input type="submit" value="GO" />
</span>',
  'seo_method' => 'pathinfo',
  'seo_level' => '0',
  'passport_safekey' => 'safekey',
  'editor_root' => '',
  'editor_toolbar' => '',
  'passport_ucpre_pw' => md5($random_str),
  'login_gdcode' => '0',
  'passport_root' => $host . 'passport/',
  'debug_open' => '0',
  'admin_host_api' => $host2 . 'admin/',
  'passport_root_api' => $host2 . 'passport/',
  'passport_api_hash' => substr(md5($random_str),0,16),
  'passport_message' => '1',
  'passport_api_time' => '300',
  'admin_gdcheck' => '0',
  'admin_host_auto' => '0',
  'timeadd' => '0',
  'passport_money' => 
  array (
    0 => '0',
    1 => '1',
    2 => '2',
  ),
  'passport_moneyhuilv' => '1',
  'passport_chongzhi_min' => '5',
  'passport_chongzhi' => '1',
  'passport_onlinepay' => '0',
  'passport_onlinemoney' => '0',
  'olpay_info' => 
  array (
    'alipay' => 
    array (
    ),
    'tenpay' => 
    array (
    ),
    'paypal' => 
    array (
    ),
    '99bill' => 
    array (
    ),
  ),
  'site_name' => 'SUNFrame',
  'uc_use' => '0',
  'passport_uc_return' => 'forward',
  'passport_grade_start' => '2000',
  'passport_money_start' => 
  array (
    0 => '10',
    1 => '0',
    2 => '0',
    3 => '0',
    4 => '0',
    5 => '0',
    6 => '0',
    7 => '',
    8 => '',
    9 => '',
  ),
  'ip_ban' => '0',
  'ip_ban_allow' => '',
  'ip_ban_ban' => '',
  'ip_ban_allsite' => '0',
)
?>