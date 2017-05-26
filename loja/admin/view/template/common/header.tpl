<!DOCTYPE html>

<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<head>

<meta charset="UTF-8" />

<title><?php echo $title; ?></title>

<base href="<?php echo $base; ?>" />

<?php if ($description) { ?>

<meta name="description" content="<?php echo $description; ?>" />

<?php } ?>

<?php if ($keywords) { ?>

<meta name="keywords" content="<?php echo $keywords; ?>" />

<?php } ?>

<?php foreach ($links as $link) { ?>

<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />

<?php } ?>

<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />

<?php foreach ($styles as $style) { ?>

<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />

<?php } ?>

<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>

<!--modulo body layout-->
<link rel="stylesheet" href="view/javascript/colorpick/css/colorpicker.css" type="text/css" />
<script type="text/javascript" src="view/javascript/colorpick/js/colorpicker.js"></script>
<!--end modulo body layout-->

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>

<link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />

<script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>

<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>

<?php foreach ($scripts as $script) { ?>

<script type="text/javascript" src="<?php echo $script; ?>"></script>

<?php } ?>

<script type="text/javascript">

//-----------------------------------------

// Confirm Actions (delete, uninstall)

//-----------------------------------------

$(document).ready(function(){

    // Confirm Delete

    $('#form').submit(function(){

        if ($(this).attr('action').indexOf('delete',1) != -1) {

            if (!confirm('<?php echo $text_confirm; ?>')) {

                return false;

            }

        }

    });

    	

    // Confirm Uninstall

    $('a').click(function(){

        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {

            if (!confirm('<?php echo $text_confirm; ?>')) {

                return false;

            }

        }

    });

});

</script>

</head>

<body>

<div id="container">

<div id="header">

  <div class="div1">

      <div class="div2"><img style="height: 65px; background: #FFF;" src="view/image/logo.png" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo $home; ?>'" /></div>

    <?php if ($logged) { ?>

    <div class="div3"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>

    <?php } ?>

  </div>

  <?php if ($logged) { ?>

  <div id="menu">

    <ul class="left" style="display: none;">

      <li id="dashboard"><a href="<?php echo $home; ?>" class="top"><?php echo $text_dashboard; ?></a></li>

      <li id="catalog"><a class="top"><?php echo $text_catalog; ?></a>

        <ul>

         <?php if ($this->user->hasPermission('access', 'catalog/category')) { ?>

          <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'catalog/product')) { ?>

          <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>

          <?php }?>

          <li><a class="parent"><?php echo $text_attribute; ?></a>

            <ul>

             <?php if ($this->user->hasPermission('access', 'catalog/attribute')) { ?>

              <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>

              <?php }?>

              <?php if ($this->user->hasPermission('access', 'catalog/attribute_group')) { ?>

              <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>

              <?php }?>

            </ul>

          </li>

          <?php if ($this->user->hasPermission('access', 'catalog/option')) { ?>

          <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'catalog/manufacturer')) { ?>

          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'catalog/download')) { ?>

          <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>

           <?php }?>

           <?php if ($this->user->hasPermission('access', 'catalog/review')) { ?>

          <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'catalog/information')) { ?>

          <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>

          <?php }?>
   
            <?php if ($this->user->hasPermission('access', 'design/banner')) { ?> 

              <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>

              <?php }?>       

        </ul>

      </li>
    
    
    <?php if ($this->user->hasPermission('access', 'extension/module')) { ?>
    
      <li id="extension"><a class="top"><?php echo $text_extension; ?></a>

        <ul>

        <?php if ($this->user->hasPermission('access', 'extension/module')) { ?>

          <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'extension/shipping')) { ?>

          <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'extension/payment')) { ?>

          <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'extension/total')) { ?>

          <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'extension/feed')) { ?>

          <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>

          <?php }?>

        </ul>

      </li>

<?php }?>
      <li id="sale"><a class="top"><?php echo $text_sale; ?></a>

        <ul>

        <?php if ($this->user->hasPermission('access', 'sale/order')) { ?>

          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>

         <?php }?> 

         <?php if ($this->user->hasPermission('access', 'sale/return')) { ?>

          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>

          <?php }?>

          <li><a class="parent"><?php echo $text_customer; ?></a>

            <ul>

            <?php if ($this->user->hasPermission('access', 'sale/customer')) { ?>

              <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>

             <?php }?> 

             <?php if ($this->user->hasPermission('access', 'sale/customer_group')) { ?>

              <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>

              <?php }?>

              <?php if ($this->user->hasPermission('access', 'sale/customer_blacklist')) { ?>

              <li><a href="<?php echo $customer_blacklist; ?>"><?php echo $text_customer_blacklist; ?></a></li>

              <?php }?>

            </ul>

          </li>

          <?php if ($this->user->hasPermission('access', 'sale/affiliate')) { ?>

          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'sale/coupon')) { ?>

          <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>

          <?php }?>

          <li><a class="parent"><?php echo $text_voucher; ?></a>

            <ul>

            <?php if ($this->user->hasPermission('access', 'sale/voucher')) { ?>

              <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>

              <?php }?>

              <?php if ($this->user->hasPermission('access', 'sale/voucher_theme')) { ?>

              <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>

              <?php }?>

            </ul>

          </li>

          <?php if ($this->user->hasPermission('access', 'sale/contact')) { ?>

          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>

          <?php }?>
          
          <li><a href="<?php echo $this->url->link('sale/newssubscribers', 'token=' . $this->session->data['token'], 'SSL');?>">Newsletter</a></li>

        </ul>

      </li>

      <li id="system"><a class="top"><?php echo $text_system; ?></a>

        <ul>

        <?php if ($this->user->hasPermission('access', 'setting/store')) { ?>

          <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>

          <?php }?>
          
          <?php if ($this->user->hasPermission('access', 'design/layout')) { ?>

          <li><a class="parent"><?php echo $text_design; ?></a>

            <ul>

            <?php if ($this->user->hasPermission('access', 'design/layout')) { ?>

              <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>

             <?php }?>

           

            </ul>

          </li>
         <?php }?> 
          
        <?php if ($this->user->hasPermission('access', 'user/user')) { ?>
        
          <li><a class="parent"><?php echo $text_users; ?></a>

            <ul>

            <?php if ($this->user->hasPermission('access', 'user/user')) { ?>

              <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>

            <?php }?>

            <?php if ($this->user->hasPermission('access', 'user/user_permission')) { ?>  

              <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>

            <?php }?>  

            </ul>

          </li>
          <?php }?>
          
		<?php if ($this->user->hasPermission('access', 'localisation/language')) { ?>  
          <li><a class="parent"><?php echo $text_localisation; ?></a>

            <ul>

            <?php if ($this->user->hasPermission('access', 'localisation/language')) { ?>

              <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>

            <?php }?>

            <?php if ($this->user->hasPermission('access', 'localisation/currency')) { ?>  

              <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>

             <?php }?> 

             <?php if ($this->user->hasPermission('access', 'localisation/stock_status')) { ?>

              <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>

             <?php }?>

             <?php if ($this->user->hasPermission('access', 'localisation/order_status')) { ?> 

              <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>

              <?php }?>

              <li><a class="parent"><?php echo $text_return; ?></a>

                <ul>

                <?php if ($this->user->hasPermission('access', 'localisation/return_status')) { ?>

                  <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>

                <?php }?> 

                <?php if ($this->user->hasPermission('access', 'localisation/return_action')) { ?> 

                  <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>

                <?php }?>  

                <?php if ($this->user->hasPermission('access', 'localisation/return_reason')) { ?>

                  <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>

                <?php }?>  

                </ul>

              </li>

              <?php if ($this->user->hasPermission('access', 'localisation/country')) { ?>

              <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>

              <?php }?>

              <?php if ($this->user->hasPermission('access', 'localisation/zone')) { ?>

              <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>

              <?php }?>

              <?php if ($this->user->hasPermission('access', 'localisation/geo_zone')) { ?>

              <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>

              <?php }?>

              <li><a class="parent"><?php echo $text_tax; ?></a>

                <ul>

                <?php if ($this->user->hasPermission('access', 'localisation/tax_class')) { ?>

                  <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>

                 <?php }?> 

                 <?php if ($this->user->hasPermission('access', 'localisation/tax_rate')) { ?> 

                  <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>

                  <?php }?>

                </ul>

              </li>

              <?php if ($this->user->hasPermission('access', 'localisation/length_class')) { ?>

              <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>

              <?php }?>

              <?php if ($this->user->hasPermission('access', 'localisation/weight_class')) { ?>

              <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>

              <?php }?>

            </ul>

          </li>
   
       <?php }?>

          <?php if ($this->user->hasPermission('access', 'tool/error_log')) { ?>

          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>

          <?php }?>

          <?php if ($this->user->hasPermission('access', 'tool/backup')) { ?>

          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>

          <?php }?>

        </ul>

      </li>

      <li id="reports"><a class="top"><?php echo $text_reports; ?></a>

        <ul>

          <li><a class="parent"><?php echo $text_sale; ?></a>

            <ul>

              <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>

              <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>

              <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>

              <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>

              <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>

            </ul>

          </li>

          <li><a class="parent"><?php echo $text_product; ?></a>

            <ul>

              <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>

              <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>

            </ul>

          </li>

          <li><a class="parent"><?php echo $text_customer; ?></a>

            <ul>

              <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>

              <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>

              <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>

              <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>

            </ul>

          </li>

          <li><a class="parent"><?php echo $text_affiliate; ?></a>

            <ul>

              <li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>

            </ul>

          </li>

        </ul>
          
      </li>

      <li id="reports"><a href="<?php echo $this->url->link('module/bodylayout', 'token=' . $this->session->data['token'], 'SSL');?>" class="top">Fundo Loja</a></li>


      <li id="reports"><a onclick="window.open('<?php echo $this->url->link('chat/chat', 'token=' . $this->session->data['token'], 'SSL');?>','','width=1000,height=400');" class="top">Chat</a></li>

    </ul>

    

    

     

    

    <ul class="right">

      <li id="store"><a onClick="window.open('<?php echo $store; ?>');" class="top"><?php echo $text_front; ?></a>

        <ul>

          <?php foreach ($stores as $stores) { ?>

          <li><a onClick="window.open('<?php echo $stores['href']; ?>');"><?php echo $stores['name']; ?></a></li>

          <?php } ?>

        </ul>

      </li>

      <li id="store"><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>

    </ul>

    <script type="text/javascript"><!--

$(document).ready(function() {

	$('#menu > ul').superfish({

		hoverClass	 : 'sfHover',

		pathClass	 : 'overideThisToUse',

		delay		 : 0,

		animation	 : {height: 'show'},

		speed		 : 'normal',

		autoArrows   : false,

		dropShadows  : false, 

		disableHI	 : false, /* set to true to disable hoverIntent detection */

		onInit		 : function(){},

		onBeforeShow : function(){},

		onShow		 : function(){},

		onHide		 : function(){}

	});

	

	$('#menu > ul').css('display', 'block');

});

 

function getURLVar(urlVarName) {

	var urlHalves = String(document.location).toLowerCase().split('?');

	var urlVarValue = '';

	

	if (urlHalves[1]) {

		var urlVars = urlHalves[1].split('&');



		for (var i = 0; i <= (urlVars.length); i++) {

			if (urlVars[i]) {

				var urlVarPair = urlVars[i].split('=');

				

				if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {

					urlVarValue = urlVarPair[1];

				}

			}

		}

	}

	

	return urlVarValue;

} 



$(document).ready(function() {

	route = getURLVar('route');

	

	if (!route) {

		$('#dashboard').addClass('selected');

	} else {

		part = route.split('/');

		

		url = part[0];

		

		if (part[1]) {

			url += '/' + part[1];

		}

		

		$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');

	}

});

//--></script> 

  </div>

  <?php } ?>

</div>

