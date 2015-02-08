<?php
/*
Plugin Name: yahoo weather plugin
Plugin URI: http://iqonz.com
Description: Displying yahoo weather for multiple locations
Version: 1.1
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
    add_action('admin_init', 'yweather_init' );
    add_action("admin_menu","weather_code_add_page");
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

function yweather_init() {
        register_setting('yweather_options', 'city_unit');
         register_setting('yweather_options', 'ywstyles');
    }

function weather_code_add_page(){
add_options_page('Weather Options','Weather Options','manage_options','yahooweather_options','weather_dis_options_page');
}

 function weather_dis_options_page(){
        
 ?>
           <div class="wrap">
           <?php screen_icon(); 

           ?>
           <h1> Weather Plugin</h1>
           <form method="post" action="options.php">
               <?php settings_fields('yweather_options'); 
               $options = get_option('city_unit');
               //add_option('city_unit',$options);

                      echo $options[temp]; 
               ?>
               <p>Enter Location code/ID:<input type='text' name='city_unit[city]' value='<?php echo $options['city']; ?>' /></p>
               <p>Select tempreature unit:<select name='city_unit[temp]'>
               <option value='c' <?php selected( $options['temp'], c ); ?>>C</option>
                <option value='f' <?php selected( $options['temp'], f ); ?>>F</option>
               </select></p>
               <p><hr></p>
               <?php  //settings_fields('yweather_style'); 
               $options = get_option('ywstyles'); ?>
               <p>Select display style for output:<select name='ywstyles'>
               <option value='sheet1' <?php selected( $options, sheet1 ); ?>>Weather slider Style</option>
                <option value='sheet2' <?php selected( $options, sheet2 ); ?>>Simple  vertical Style</option>
               </select></p>
               <p class="submit">
                <?php //submit_button(); ?>
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ;?>" />
            </p>
            <!--input type="reset" name="reset" value="Reset" /-->
            <p>You can search Location codes/IDs from <a href="http://www.edg3.co.uk/snippets/weather-location-codes">Here</a> </p>
               </form>
               </div>
            <?php

            }
            add_action('wp_head', 'my_ss');
            function my_ss(){
             echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>';
             wp_enqueue_script( 'bxslider_script.js',plugins_url('js/jquery.bxslider.js', __FILE__), '4.1.2', true );
             if(get_option(ywstyles)=='sheet1'){
             wp_register_style('my_css1', plugins_url('css/style1.css',__FILE__ ));
             wp_enqueue_style('my_css1');
           }else{
            wp_register_style('my_css2', plugins_url('css/style2.css',__FILE__ ));
             wp_enqueue_style('my_css2');
           }
              wp_register_style('bxslider_css', plugins_url('/css/jquery.bxslider.css',__FILE__ ));
              wp_enqueue_style('bxslider_css');
             }
             function ss_boxslide() { 
              if(get_option(ywstyles)=='sheet1'){
                echo '<script type="text/javascript">'."jQuery(document).ready(function($){
                  jQuery('.bxslider').bxSlider({
                      adaptiveHeight: true,
                      mode: 'fade',
                      controls: false,
                      auto:true
                  });
                });".'</script>';
              }else{
                echo '<script type="text/javascript">'."jQuery(document).ready(function($){
                  jQuery('.bxslider').bxSlider({
                      adaptiveHeight: true,
                      mode: 'vertical',
                      auto:true,
                      pager:false,
                      controls:false,
                      autoControls: false,
                      slideMargin: 5
                  });
                });".'</script>';
                  }
                }
                add_action('wp_footer','ss_boxslide');
                add_shortcode('ywsc', 'yw');
                function yw(){
                            $yw1 = get_option('city_unit');
                            $loc_code=$yw1['city'];
                            $loc_code=explode(',', $loc_code);

                            //$loc_code=array('PKXX0008');
                            $arr_count=count($loc_code);
                            $temp=$yw1['temp'];
                            
                            if(empty($yw1)){
                            echo "Invalid or Empty weather location";
                            }
                     echo '<div class="weather"><div class="bxslider">';       
                foreach ($loc_code as $loc_codes) {
                //for ($i=0; $i < $arr_count; $i++) { 
                    # code...
                $weather_feed = file_get_contents("http://weather.yahooapis.com/forecastrss?p=$loc_codes&u=$temp");
                $weather = simplexml_load_string($weather_feed);
                if(!$weather) die('weather failed');

                //$channel_yweather = $weather->channel->children("http://xml.weather.yahoo.com/ns/rss/1.0");
                    $channel_yweather = $weather->registerXPathNamespace('yweather', 'http://xml.weather.yahoo.com/ns/rss/1.0');
                    $loc=$weather->channel->xpath('yweather:location');
                    $con=$weather->channel->item->xpath('yweather:condition');
                    $loc_con=array_merge($loc,$con);
                    if(get_option(ywstyles)=='sheet1'){
                    $output1='<div class="temp_text">'.$loc_con['1']['temp'].'&#176;'."</div><span class='temp unit'>".$temp.'</span>';
                    $output2='<div class="bd cond">'.$loc_con['1']['text'].'</div>';
                    $output3='<span class="yom-mod">'.$loc_con['0']['country']."|".$loc_con['0']['city'].'</span>';
                  }else{
                    $output2='<span class="temp">'.$loc_con['1']['temp'].'&#176;'.$temp.'</span></h3>';
                    $output3='<span class="date">'.$loc_con['1']['text'].'</span>';
                    $output1='<h3>'.$loc_con['0']['country']."|".$loc_con['0']['city'];
                  }
                    //echo $condition['city'];
                    //$w_ico=$loc_con['1']['code'].".gif";
                    $new_ico=$loc_con['1']['code'];
                    //echo $new_ico;
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
                    if($new_ico[0]==22){$loc[ico]=plugins_url('yahoo-weather-plugin/img/22.png');}
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
                    //$output='<img src=https://s.yimg.com/zz/combo?a/i/us/we/52/'.$w_ico.'>';
                    $output='<img src='.$loc[ico].'>';
                $html.='<div class="slide">'.$output.$output1.$output2.$output3.'</div>';
                }     
                //return $test; 
                $html.='</div></div>'; 
                echo $html;          
}
?>