<?php if(!defined('PLAYSTORE_API_URL')) die(); ?>
<h1><?php echo Playstore_API::$title ?> Variable Helper</h1>
<p>On Setting Page, you can use bracket {} to access variabel. Example</p>
<pre>
{name}        -> Will show APP Name
{version}     -> will show APP Version
{. . . }      etc
</pre>
<p>On Post Template, you can access via PHP variable on $data. Example</p>
<pre>
$data['name']    -> Will show APP Name
$data['version'] -> will show APP Version
$data[. . . ]    etc
</pre>
<p>On Post Editor, you can access via shortcodes. Example</p>
<pre>
[apk name]       -> Will show APP Name
[apk version]    -> will show APP Version
[apk  .. ]       you can see more data on post editor
</pre>
<h2>Bellow all list of available variable</h2>
<h3>APK Detail Data</h3>
<p>Some variable maybe contain 'NULL', its depend on data on playstore</p>
<pre>
package            -> APP Id or Package Name
name               -> APP Name
creator            -> Creator name
icon               -> Small icon
cover              -> Large icon
banner             -> Image large banner  if any or "null"
video              -> Video URL if any or "null"
video_image        -> Video image if any or "null"
screenshot         -> Array or screenshot URL
type               -> APP type, Application or Game
category           -> APP Category (formatted)
category_ori       -> APP Category (Not formatted)
size               -> APK Size in bytes
version            -> APP Version
version_code       -> Version Code
upload_date        -> Last Update
price              -> Price numeric or String "Free"
price_currency     -> price currency
price_micros       -> price micros
developer_name     -> Developer Name / ID
developer_email    -> Developer Email
developer_website  -> Developer Website
downloads          -> Total Download
comments           -> null
rating    -> Array {
            type                -> Rating type
            star_rating         -> Average rating five based
            ratings_count       -> Total rating count
            one_star_ratings    -> Count of user that give one star
            two_star_ratings    -> Count of user that give two star
            three_star_ratings  -> Count of user that give three star
            four_star_ratings   -> Count of user that give four star
            five_star_ratings   -> Count of user that give five star
            comment_count       -> Count of comment
        }
rating_percent     -> Rating in percentage
permission         -> Array of Android permissions
url                -> Playstore URL
description        -> HTML Description
recent_changes     -> What New
</pre>
<h3>Other Data Variabel</h3>
<p>This variable can be used too</p>
<pre>
-- WEB VARIABLE  --
domain         -> Current domain name
uid            -> Current User ID

-- DATE VARIABLE --
year           -> Current year
month          -> Current month (01-12)
month_name     -> Current month name (Jan-Dec)
month_name_long-> Current month name (January-December)
day            -> Current day in month
hour           -> Current hour with zero (00-23)
minute         -> Current minute (00-59)
second         -> Curent second with zero (00-59)
</pre>