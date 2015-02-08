<?php
/*
Plugin Name: yahoo weather plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Displying yahoo weather
Version: 1.0
Author: Muhammad Yasir khan
Author URI: http://zykhan.wordpress.com
License: Under GPL2
	Copyright 2012 zykhan (email : sir_yasir@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (is_admin()) {
register_activation_hook(__FILE__, 'ywp_install');
}
function ywp_install() {
$options=array(
'city' =>'PKXX0008',
'temp' =>'c',
);
update_option('city_unit',$options);
}
add_action("admin_menu","weather_code_add_page");

function weather_code_add_page(){
add_options_page('Weather Options','Weather Options','manage_options','displaying_weather_options','weather_dis_options_page');
}
 function weather_dis_options_page(){
 ?>
           <div class="wrap">
           <?php screen_icon(); ?>
           <h1> Weather Plugin</h1>
           <form action="" method="post" >
               <p>Enter Location code/ID:<input name="yw1" id="yw1" type="text" value="<?php echo $_POST['yw1'] ?>" /></p>
               <p>Select tempreature unit:<select name="unit">
               <option value="c" name="yw2">C</option>
               <option value="f" name="yw2">F</option>
               </select></p>
             <input type="submit" name="submit" value="Save Changes" />
            <input type="reset" name="reset" value="Reset" />
            <p>You can search Location codes/IDs from <a href="http://www.edg3.co.uk/snippets/weather-location-codes">Here</a> </p>
               </form>
               </div>
            <?php
            $options=array('city'=>$_POST['yw1'],'temp'=>$_POST['unit']);
             if(isset($_POST['submit'])){
             if(empty($_POST['yw1'])){
               ?>
               <p><div id="message" class="error"> Please Enter City Code</div></p>
             <?php }else{
                $yw1=$_POST['yw1'];
                add_option('city_unit', $options);
                update_option('city_unit', $options);?>
           <p><div id="message" class="updated"><p>Settings saved successfully</p> </div></p>
            <?php }
            }
            }
            add_action('wp_head', 'my_ss');
            function my_ss(){
             wp_register_style('my_css', plugins_url('style.css',__FILE__ ));
             wp_enqueue_style('my_css');
             }
             add_shortcode('ywsc', 'yw');
            function yw(){
            $yw1 = get_option('city_unit');
            $city=$yw1['city'];
            $temp=$yw1['temp'];
            if(empty($yw1)){
            echo "Invalid or Empty weather location";
            }
   $doc = new DOMDocument();
   $doc->load('http://weather.yahooapis.com/forecastrss?p='.$city.'&u='.$temp.'');
    if(!$doc){
    echo "Yahoo Weather not responding";
    }
    //'root'
    $channel = $doc->getElementsByTagName("channel");
    //each item withing $channel

    foreach($channel as $chnl)
    {
             //element in loop
    $item = $chnl->getElementsByTagName("item");
    foreach($item as $itemgotten)
    {
    //'$item' for the element "description"
    $describe = $itemgotten->getElementsByTagName("description");
    //once I find it I create a variable named "$description" and assign the value of the Element to it
    $description = $describe->item(0)->nodeValue;
    $icon=preg_match('/src="(.*?)"/i',$description,$im);
    preg_match('/[0-9]+\.[a-zA-Z]{2,5}/',$description,$new_ico);
    //$loc['ico']=$im[0];

    if($new_ico[0]==0){$loc[ico]=plugins_url('yahoo-weather-plugin/img/0.png');}
    if($new_ico[0]==1){$loc[ico]=plugins_url('yahoo-weather-plugin/img/1.png');}
    if($new_ico[0]==2){$loc[ico]=plugins_url('yahoo-weather-plugin/img/2.png');}
    if($new_ico[0]==3){$loc[ico]=plugins_url('yahoo-weather-plugin/img/3.png');}
    if($new_ico[0]==4){$loc[ico]=plugins_url('yahoo-weather-plugin/img/4.png');}
    if($new_ico[0]==5){$loc[ico]=plugins_url('yahoo-weather-plugin/img/5.png');}
    if($new_ico[0]==6){$loc[ico]=plugins_url('yahoo-weather-plugin/img/6.png');}
    if($new_ico[0]==7){$loc[ico]=plugins_url('yahoo-weather-plugin/img/7.png');}
    if($new_ico[0]==8){$loc[ico]=plugins_url('yahoo-weather-plugin/img/8.png');}
    if($new_ico[0]==9){$loc[ico]=plugins_url('yahoo-weather-plugin/img/9.png');}

    if($new_ico[0]==10){$loc[ico]=plugins_url('yahoo-weather-plugin/img/10.png');}
    if($new_ico[0]==11){$loc[ico]=plugins_url('yahoo-weather-plugin/img/11.png');}
    if($new_ico[0]==12){$loc[ico]=plugins_url('yahoo-weather-plugin/img/12.png');}
    if($new_ico[0]==13){$loc[ico]=plugins_url('yahoo-weather-plugin/img/13.png');}
    if($new_ico[0]==14){$loc[ico]=plugins_url('yahoo-weather-plugin/img/14.png');}
    if($new_ico[0]==15){$loc[ico]=plugins_url('yahoo-weather-plugin/img/15.png');}
    if($new_ico[0]==16){$loc[ico]=plugins_url('yahoo-weather-plugin/img/16.png');}
    if($new_ico[0]==17){$loc[ico]=plugins_url('yahoo-weather-plugin/img/17.png');}
    if($new_ico[0]==18){$loc[ico]=plugins_url('yahoo-weather-plugin/img/18.png');}
    if($new_ico[0]==19){$loc[ico]=plugins_url('yahoo-weather-plugin/img/19.png');}
    if($new_ico[0]==20){$loc[ico]=plugins_url('yahoo-weather-plugin/img/20.png');}

    if($new_ico[0]==21){$loc[ico]=plugins_url('yahoo-weather-plugin/img/21.png');}
    if($new_ico[0]==23){$loc[ico]=plugins_url('yahoo-weather-plugin/img/22.png');}
    if($new_ico[0]==23){$loc[ico]=plugins_url('yahoo-weather-plugin/img/23.png');}
    if($new_ico[0]==24){$loc[ico]=plugins_url('yahoo-weather-plugin/img/24.png');}
    if($new_ico[0]==25){$loc[ico]=plugins_url('yahoo-weather-plugin/img/25.png');}
    if($new_ico[0]==26){$loc[ico]=plugins_url('yahoo-weather-plugin/img/26.png');}
    if($new_ico[0]==27){$loc[ico]=plugins_url('yahoo-weather-plugin/img/27.png');}
    if($new_ico[0]==28){$loc[ico]=plugins_url('yahoo-weather-plugin/img/28.png');}
    if($new_ico[0]==29){$loc[ico]=plugins_url('yahoo-weather-plugin/img/29.png');}
    if($new_ico[0]==30){$loc[ico]=plugins_url('yahoo-weather-plugin/img/30.png');}

    if($new_ico[0]==31){$loc[ico]=plugins_url('yahoo-weather-plugin/img/31.png');}
    if($new_ico[0]==32){$loc[ico]=plugins_url('yahoo-weather-plugin/img/32.png');}
    if($new_ico[0]==33){$loc[ico]=plugins_url('yahoo-weather-plugin/img/33.png');}
    if($new_ico[0]==34){$loc[ico]=plugins_url('yahoo-weather-plugin/img/34.png');}
    if($new_ico[0]==35){$loc[ico]=plugins_url('yahoo-weather-plugin/img/35.png');}
    if($new_ico[0]==36){$loc[ico]=plugins_url('yahoo-weather-plugin/img/36.png');}
    if($new_ico[0]==37){$loc[ico]=plugins_url('yahoo-weather-plugin/img/37.png');}
    if($new_ico[0]==38){$loc[ico]=plugins_url('yahoo-weather-plugin/img/38.png');}
    if($new_ico[0]==39){$loc[ico]=plugins_url('yahoo-weather-plugin/img/39.png');}
    if($new_ico[0]==40){$loc[ico]=plugins_url('yahoo-weather-plugin/img/40.png');}

    if($new_ico[0]==41){$loc[ico]=plugins_url('yahoo-weather-plugin/img/41.png');}
    if($new_ico[0]==42){$loc[ico]=plugins_url('yahoo-weather-plugin/img/42.png');}
    if($new_ico[0]==43){$loc[ico]=plugins_url('yahoo-weather-plugin/img/43.png');}
    if($new_ico[0]==44){$loc[ico]=plugins_url('yahoo-weather-plugin/img/44.png');}
    if($new_ico[0]==45){$loc[ico]=plugins_url('yahoo-weather-plugin/img/45.png');}
    if($new_ico[0]==46){$loc[ico]=plugins_url('yahoo-weather-plugin/img/46.png');}
    if($new_ico[0]==47){$loc[ico]=plugins_url('yahoo-weather-plugin/img/47.png');}

    //and display it on-screen
    //print_r($description);
    $yw_arr=array('location','units','condition');
    $yw_url="http://xml.weather.yahoo.com/ns/rss/1.0";
               if($chnl->hasAttribute('city')){
           echo "Empty element found";
           }
            $loc[] = $chnl->getElementsByTagNameNS("$yw_url","$yw_arr[0]")->item(0)->getAttribute('city');
            $loc[] = $chnl->getElementsByTagNameNS("$yw_url","$yw_arr[0]")->item(0)->getAttribute('country');
            $loc[] = $chnl->getElementsByTagNameNS("$yw_url","$yw_arr[2]")->item(0)->getAttribute('temp');
            $loc[] = $chnl->getElementsByTagNameNS("$yw_url","$yw_arr[1]")->item(0)->getAttribute('temperature');
            $loc[] = $chnl->getElementsByTagNameNS("$yw_url","$yw_arr[2]")->item(0)->getAttribute('text');
            $final='<span style="color:#ffffff;">'.$loc[0].",".$loc[1].'</span>'  ." | ";
            $final2= "<span class=\"box\"><img width=\"50\" height=\"45\" title=\" $loc[4] \" src=\"$loc[ico]\" /></span> ";
              $final1='<span class="temp_text">'.$loc[2]."&#176"." ".$loc[3].'</span>';
                return '<div class="w_box">'.$final. $final2 . $final1.'</div>';
            }
    }
}
?>