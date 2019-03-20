<?php if(!defined('PLAYSTORE_API_URL')) die(); ?>
<table class="table apk_data code">
<?php playstore_api_shortcodes_printer(self::$var['apk_data']) ?>
</table>
<?php

function playstore_api_shortcodes_printer(&$data, $pre = 'apk ')
{
	foreach ($data as $key => &$value)
	{
		if(is_array($value)){
			playstore_api_shortcodes_printer($value, $pre . $key . ' ');
		}else{
			printf('<tr><th>[%s]</th><td>%s</td></tr>', $pre.$key, is_null($value) ? '<i>NULL</i>' : $value );
		}
	}
}