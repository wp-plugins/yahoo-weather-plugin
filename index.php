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
    //now I get all elements inside this document with the following name "channel", this is the 'root'
    $channel = $doc->getElementsByTagName("channel");
    //now I go through each item withing $channel

    foreach($channel as $chnl)
    {
             //I then find the 'item' element inside that loop
    $item = $chnl->getElementsByTagName("item");
    foreach($item as $itemgotten)
    {
    //now I search within '$item' for the element "description"
    $describe = $itemgotten->getElementsByTagName("description");
    //once I find it I create a variable named "$description" and assign the value of the Element to it
    $description = $describe->item(0)->nodeValue;
    $icon=preg_match('/src="(.*?)"/i',$description,$im);
    $loc['ico']=$im[0];
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
            $final=$loc[0].",".$loc[1]." |";
            $final2= "<span class=\"box\"><img width=\"33\" height=\"33\" title=\" $loc[4] \" $loc[ico] /></span> ";
              $final1=$loc[2]."&#176"." ".$loc[3]."".$loc[5];
                return $final. $final2 . $final1;
            }
    }
}
?>