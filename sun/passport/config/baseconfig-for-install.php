<?php
 $base_config = array (
  'admin_host_self' => $host . 'passport/admin/',
  'passport_root' => $host . 'passport/',
  'reg_open' => '1',
  'why_closereg' => 'NO',
  'username_len' => '5',
  'pass_len' => '6',
  'username_pattern' => '^[_a-zA-Z0-9]+$',
  'pattern_method' => 'ereg',
  'email_check' => '1',
  'reg_gdcode' => '0',
  'login_gdcode' => '1',
  'passport_modify' => '1',
  'passport_message' => '1',
  'username_pattern_descrip' => 'a-zA-Z0-9_',
  'passport_method' => '0',
  'passport_api_hash' => md5($random_str),
  'passport_api_address' => '',
  'passport_api_program' => 'dz',
  'username_ban' => 'ppframe,admin,fuck',
  'default_group' => '0',
  'passport_moneycard_use' => '0',
  'passport_moneycard_dotype' => '0',
  'passport_moneycard_gdcode' => '1',
  'passport_server' => '0',
  'ip_ban' => '0',
  'ip_ban_login' => '0',
  'ip_ban_reg' => '0',
  'passport_reg_expired' => '0',
  'expired_ban' => '1',
)
?>