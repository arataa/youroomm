<?php
/*
 * Template Name: Management
 */

// post process
if($_SERVER["REQUEST_METHOD"] == "POST"){
  //print_r($_POST);
  //print_r($_FILES);
  //echo wpmem_login();

  //user registration
  if ( is_user_logged_in() == false ) {
    include_once('wp-content/plugins/wp-members/wp-members-register.php');
    $simple_local_avatars = new Simple_Local_Avatars;

    //login name
    $_POST['log'] = 'user_'.rand().'_'.date('Ymdhms') ;

    if ( strcmp( $_POST["user_email"], $_POST["email2"]) != 0 ) {
      //email is validation
      $res = "Email is not match";
    }elseif (empty($_POST["password"])) {
      //password is not valid
      $res = "Password is not set";
    }else{
      $res = wpmem_registration('register');
    }

    if ( strcmp($res, "success") == 0 ) {
      //registration success
      $_POST['a']   = "login";
      $_POST['pwd'] = $_POST['password'];
      //$simple_local_avatars->admin_init();
      $_POST['redirect_to'] = get_permalink( get_page_by_title('Signup')) ;

      $creds = array();
      $creds['user_login']    = $_POST['log'];
      $creds['user_password'] = $_POST['pwd'];
      $current_user = wp_signon( $creds, false );
      update_user($current_user);

      foreach ( $_POST['posts'] as $key=>$post_post ) {
        if ( empty($key) || (int)$key < 0 ) {
          $res = update_post("", $post_post, $current_user);
        }else{
          $res = update_post(get_post($key), $post_post, $current_user);
        }
      }
      if ( $res == true ) {
        echo wpmem_login();
      }else{
        echo $res;
      }
    }
  }

  if ( is_user_logged_in() == true ) {
    // user update
    update_user($current_user);
      //print_r( $_POST['posts'] );

    foreach ( $_POST['posts'] as $key=>$post_post ) {
      if ( empty($key) ) {
        $res = update_post("", $post_post, $current_user);
      }else{
        $res = update_post(get_post($key), $post_post, $current_user);
      }
      if ( $res != true ) {
        echo $res;
        break;
      }
    }
    if ($res == true) wp_redirect(get_permalink( get_page_by_title('Signup')) );
    return ;
  }
}

if ( empty($_POST['last_name']) )  $_POST['last_name']  = get_the_author_meta('last_name',$current_user->ID);
if ( empty($_POST['first_name']) ) $_POST['first_name'] = get_the_author_meta('first_name',$current_user->ID);
if ( empty($_POST['user_email']) ) $_POST['user_email'] = get_the_author_meta('user_email',$current_user->ID);
if ( empty($_POST['addr1']) )      $_POST['addr1']      = get_the_author_meta('addr1',$current_user->ID);
if ( empty($_POST['city']) )       $_POST['city']       = get_the_author_meta('city',$current_user->ID);
if ( empty($_POST['country']) )    $_POST['country']    = get_the_author_meta('country',$current_user->ID);
if ( empty($_POST['zip']) )        $_POST['zip']        = get_the_author_meta('zip',$current_user->ID);
if ( empty($_POST['phone1']) )     $_POST['phone1']     = get_the_author_meta('phone1',$current_user->ID);
if ( empty($_POST['sex']) )        $_POST['sex']        = get_the_author_meta('sex',$current_user->ID);

if ( empty($_POST['email2']) )     $_POST['email2']     = get_the_author_meta('user_email',$current_user->ID);
if ( empty($_POST['birthday1']) )  $_POST['birthday1']  = get_the_author_meta('birthday1',$current_user->ID);
if ( empty($_POST['birthday2']) )  $_POST['birthday2']  = get_the_author_meta('birthday2',$current_user->ID);
if ( empty($_POST['birthday3']) )  $_POST['birthday3']  = get_the_author_meta('birthday3',$current_user->ID);
if ( empty($_POST['stay_years']) ) $_POST['stay_years'] = get_the_author_meta('stay_years',$current_user->ID);
if ( empty($_POST['visit_countries']) ) $_POST['visit_countries'] = get_the_author_meta('visit_countries',$current_user->ID);
if ( empty($_POST['want_visit_countries']) ) $_POST['want_visit_countries'] = get_the_author_meta('want_visit_countries',$current_user->ID);
if ( empty($_POST['hobby']) ) $_POST['hobby']     = get_the_author_meta('hobby',$current_user->ID);
if ( empty($_POST['comment']) ) $_POST['comment'] = get_the_author_meta('comment',$current_user->ID);

get_header();
?>
<link href="<?php bloginfo('template_url'); ?>/css/management.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/jquery-ui.css" />
<script src="<?php bloginfo('template_url'); ?>/js/jquery-ui.min.js"></script>

<script src="<?php bloginfo('template_url'); ?>/js/management.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/jquery.upload-1.0.2.min.js"></script>
<script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
   <script>
  $(function() {
    $( "#datepicker2" ).datepicker();
  });
</script>


<div class="yourm_wrapper2">

<form id="profile" method="POST" action="<?php echo get_permalink( get_page_by_title('Management'))?>" >

  <a class="bgtitle">管理画面</a>
  <?php if( !empty($res) ) echo $res ; ?>
  <div class="authorprof2">
    <div  class="subtitle">
      <a class="text1">オーナープロフィール</a>
      <a class="text2">※赤い枠のみプロフィールに表示されます。</a>
    </div>
    <div class="border"></div>
    <div class="picowner">
      <a href="#">
        <?php $local_avatars = get_user_meta( $current_user->ID, 'simple_local_avatar', true ); ?>
        <img id="profile_image" src="<?php echo $local_avatars['full'] ?>" width='124px' height='124px' />
        <input id="image_url" type="text" name="image_url" value="" style="display:none;" >
      </a>
      <img id="image_browse" src=<?php image_path("management/browse.jpg") ?> style="float:right;"/>
      <input type="file" name="upload" id="input_profile" class="profile" style="display:none;" />
    </div>
    <div class="inputprof1">
      <table class="leftinfo" width="370"height="380" border="0" cellspacing="4" cellpadding="0">
        <tr>
          <td width="88" align="right"><span style="color:red;">*</span>苗字</td>
          <td width="98"><input name="last_name" value="<?php echo $_POST['last_name'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:96px;height:22px;"></td>
         <td width="62" align="right"><span style="color:red;">*</span>名前</td>
         <td colspan="3" align="right"><input name="first_name" value="<?php echo $_POST['first_name'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:96px;height:22px;"></td>
       </tr>
       <tr>
  <td align="right"><span style="color:red;">*</span>性別</td>
  <td>
  <?php
    $val = empty($_POST['sex'])? get_the_author_meta('sex',$current_user->ID) : $_POST['sex'] ;
    echo wpmem_create_formfield('sex','select',array('女性|1','男性|2'),$val,'sex');
  ?>
  </td>
  <td colspan="3">&nbsp;</td>
  <td width="92">&nbsp;</td>
</tr>
<tr>
  <td align="right">誕生日</td>
  <td align="left" valign="middle">
  <?php
    $val   = empty($_POST['birthday1'])? get_the_author_meta('birthday1',$current_user->ID) : $_POST['birthday1'] ;
    $years = array();
    for ( $i = 1900 ; $i <= 2000 ; $i++ ) { array_push($years,$i.'|'.$i); };
    echo wpmem_create_formfield('birthday1','select',$years,$val,'birthday1');
  ?>
  &nbsp;年</td>

  <td colspan="3" align="left">
  <?php
    $val    = empty($_POST['birthday2'])? get_the_author_meta('birthday2',$current_user->ID) : $_POST['birthday2'] ;
    $months = array();
    for ( $i = 1 ; $i <= 12 ; $i++ ) { array_push($months,$i.'|'.$i); };
    echo wpmem_create_formfield('birthday2','select',$months,$val,'birthday2');
  ?>
  &nbsp;月</td>
  <td>
  <?php
    $val  = empty($_POST['birthday3'])? get_the_author_meta('birthday3',$current_user->ID) : $_POST['birthday3'] ;
    $days = array();
    for ( $i = 1 ; $i <= 31 ; $i++ ) { array_push($days,$i.'|'.$i); };
    echo wpmem_create_formfield('birthday3','select',$days,$val,'birthday3');
  ?>
  &nbsp;日</td>
</tr>
<tr>

<td align="right"><span style="color:red;">*</span>Eメール<span style="font-size:8px;"><br>
（ログインID）</span></td>
<td colspan="5"><input name="user_email" value="<?php echo $_POST['user_email'] ?>" type="text" style="border:1px black solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>確認用Eメール</td>
<td colspan="5"><input name="email2" value="<?php echo $_POST['email2'] ?>" type="text" style="border:1px black solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>パスワード</td>
<td colspan="5"><input name="password" value="<?php echo $current_user->user_pass ?>" type="password" style="border:1px black solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>電話番号</td>
<td colspan="5"><input name="phone1" value="<?php echo $_POST['phone1'] ?>"  type="text" style="width:240px;height:22px;border:1px black solid;float:left; border-radius:3px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>住所</td>
<td colspan="5"><input name="addr1" value="<?php echo $_POST['addr1'] ?>" type="text" style="border:1px black solid;float:left; border-radius:3px;width:240px;height:22px;border:1px black solid;float:left; border-radius:3px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>市</td>
<td colspan="5"><input name="city" value="<?php echo $_POST['city'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>国</td>
<td colspan="5"><input name="country" value="<?php echo $_POST['country'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
<td align="right"><span style="color:red;">*</span>郵便番号</td>
<td colspan="5"><input name="zip" value="<?php echo $_POST['zip'] ?>" type="text" style="border:1px black solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
</table>
<table class="rightinfo"width="350" height="380" border="0" cellspacing="4" cellpadding="0">
<tr>
  <td width="96" align="right"><span style="color:red;">*</span>大陸</td>
  <td colspan="2">
  <?php
    $val  = empty($_POST["continent"]) ? get_the_author_meta('continent',$current_user->ID) : $_POST["continent"] ;
    echo wpmem_create_formfield('continent','select',get_continents($continents),$val,'continent');
  ?>
  </td>
</tr>
<tr>
  <td height="34" align="right">海外在住歴</td>
  <td width="149"><input name="stay_years" value="<?php echo $_POST['stay_years'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:px;height:22px;"></td>
  <td width="93">年</td>
</tr>
<tr>
  <td align="right">行った事がある国</td>
  <td colspan="2"><input name="visit_countries" value="<?php echo $_POST['visit_countries'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
  <td align="right">行きたい国</td>
  <td colspan="2"><input name="want_visit_countries" value="<?php echo $_POST['want_visit_countries'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
  <td align="right">趣味</td>
  <td colspan="2"><input name="hobby" value="<?php echo $_POST['hobby'] ?>" type="text" style="border:1px red solid;float:left; border-radius:3px;width:240px;height:22px;"></td>
</tr>
<tr>
  <td align="right">コメント</td>
  <td colspan="2"><textarea name="comment" cols="" rows="" style="height:190px;width:240px;border:1px red solid;float:left; border-radius:3px;"><?php echo $_POST['comment'] ?></textarea></td>
</tr>
</table>
</div>
</div>
<!----above authorprof end---->
<!----bellow  info start---->

<?php
  if ( $current_user->ID > 0 ) {
    query_posts( array('author' => $current_user->ID, 'orderby' => 'id', 'order' => 'ASC', ) );
    if ( have_posts() ) {
      while ( have_posts() ) : the_post();
        //wp_get_attachment_image( $image1[id], "medium");
        echo property_profile($post);
      endwhile;
    }else{
      echo property_profile("");
    }
  }else{
    echo property_profile("");
  }
?>
<div class="addmore"><a href="#" onclick="return false;" id="add_property" >
<img src="<?php image_path("management/btn_add.jpg") ?>"/></a></div>

<div class="selectbtn">
<a href="#" onclick="submit();return false;" ><img src="<?php image_path("management/save_btn.jpg")?>" /></a>
</div>

</form>

</div>
<!----below lendinfo end---->
</div>
<!----youroomwrapper end---->
</div>

<?php get_footer(); ?>
