<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

namespace cyr\junhuan;

define('PAY_URL', 'https://www.smtsvspay.com/cz/interface/wxpt/payOrder.do');
define('PAY_REWRITE', 'https://www.smtsvspay.com/cz/interface/wxpt/notificationConfirm.do');
define('PAY_QUERY', 'https://www.smtsvspay.com/cz/interface/wxpt/findOrder.do');
define('PAY_GETCERT', 'https://www.smtsvspay.com/cz/interface/wxpt/PaymentCertification.do ');

define('TRANSAC_GETCERT', 'TMRI_PAYMENT_CERTIFICATION');
define('TRANSAC_PAYCREATE', 'TMRI_ORDER_CREATE');
define('TRANSAC_REWRITE', 'TMRI_ORDER_NOTIFY_CONFIRM');
define('TRANSAC_QUERY', 'TMRI_ORDER_QUERY');
