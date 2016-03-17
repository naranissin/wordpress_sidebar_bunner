<?php
/*
Plugin Name: サイドバー・おすすめ特集下段ウィジェット
Plugin URI: http://www.paka3.com/wpplugin
Description: ウィジェットを自分で作成：メディアアップローダで写真・画像を選択
Author: Shoji ENDO
Version: 0.1
Author URI:http://www.paka3.com/
*/

//ウィジェット生成用アクションフィルタ
add_action('widgets_init', create_function('', 'return register_widget("Paka3ImgWidget");'));

class Paka3ImgWidget extends WP_Widget {
    //コンストラクタ：オブジェクト生成時呼び出し
    public function __construct(){
        //admin_print_scripts-管理画面のページ名
        add_action('admin_print_scripts-widgets.php', array($this,'admin_scripts')); 
	//名前
	$name = "おすすめ特集下段";
	//説明文
	$widget_ops = array( 'description' => 'おすすめ特集下段' );
	//管理画面でのサイズ
        $control_ops = array( 'width' => 200, 'height' => 200 );
        parent::WP_Widget(false, $name,$widget_ops,$control_ops);
    }

    //ウィジェット：画面表示
    public function widget($args, $instance) {		
        extract( $args );
        $title = $instance['title'];
		$url = $instance['url'];
        $title = $title ? $before_title.$title.$after_title: "";
        $str = apply_filters('paka3image_Wedget', $instance['paka3image']);
        $str = $str ? $str: "";
	echo <<<EOS
              {$before_widget}
                  {$title}
                  <a href="{$url}" target="_blank">{$str}</a>
				  
              {$after_widget}
EOS;
    }

    //更新時の処理
    public function update($new_instance, $old_instance) {				
	//入力した値を更新します
	return $new_instance;
    }

    //管理画面：ウィジェット画面
    function form($instance) {				
    $title = esc_attr($instance['title']);
	$f_id   = $this->get_field_id('title');
	$f_name = $this->get_field_name('title');
	$mess = __('Title:');
        
	$url = esc_attr($instance['url']);
	$f_url_id   = $this->get_field_id('url');
	$f_url_name = $this->get_field_name('url');
	$mess = __('Title:');	
	
        $paka3image = $instance['paka3image'];
	$f_img_id   = $this->get_field_id('paka3image');
	$f_img_name = $this->get_field_name('paka3image')."[]";
	$mess = __('Title:');
        
       $imgHtml="";
#       foreach($paka3image as $akey => $img){
	foreach((array)$paka3image as $akey => $img){
	  $imgHtml.=<<<EOS
	   <div id="img_{$akey}">
   	    <a href="#" class="paka3image_remove">画像を削除する</a>
	    <br /><img src='{$img}'/>
	    <input type='hidden' name='{$f_img_name}' value='{$img}' />
	  </div>
EOS;
       }
       echo <<<EOS
        <style type="text/css">
	.paka3images div{
	    /*float:left;*/
	    margin: 2px 10px;
	    height: 120px;
	    overflow:hidden;
	}
        .paka3images img 
        {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #cccccc;
        }
	.paka3ImageEnd{
		clear:left
	}
	
        </style>
        <div class="wrap blockPaka3Img" >
	
        <button id="paka3media____{$f_img_id}" class="paka3media" type="button"  name="{$f_img_name}">画像を選択</button>
	<div id="box_paka3media____{$f_img_id}" class="paka3images">{$imgHtml}</div>
	<div class="paka3ImageEnd"></div>
        </div>
EOS;

        echo <<<EOS
             <p>
	      <label for="{$f_id}">{$mess}
                <input class="widefat" id="{$f_id}"
		   name="{$f_name}" type="text"
		   value="{$title}" />
	      </label>
          
		  <label for="{$f_url_id}">URL
                <input class="widefat" id="{$f_url_id}"
		   name="{$f_url_name}" type="text"
		   value="{$url}" />
	      </label>
              

	     </p>
EOS;


    }


    function admin_scripts(){
       wp_enqueue_media(); // メディアアップローダー用のスクリプトをロードする
 
       // カスタムメディアアップローダー用のJavaScript
       wp_enqueue_script(
           'my-media-uploader',
	
   	   //**javasctiptの指定
 	   //*プラグインにしたとき
           plugins_url("paka3-uploader.js", __FILE__),
           //*function.phpに記入した場合
	   //get_bloginfo( 'stylesheet_directory' ) . '/paka3-uploader.js',
	
	   array('jquery'),
           filemtime(dirname(__FILE__).'/paka3-uploader.js'),
           false
       );  
    }
}

//###############################
//3.表示部分のデータはフィルターフックしてみる
add_filter('paka3image_Wedget', my_paka3image_view);
function my_paka3image_view($data){
	$str="";
        foreach($data as $aval){
	     $str.="<img src='".$aval."' alt='".$title."' class='".hover_img."' />";
	}
	return $str;
}


?>