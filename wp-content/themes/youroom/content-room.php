<?php
/**
 * The template for displaying content in the room.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
<script src="<?php bloginfo('template_url'); ?>/js/post.js"></script>
<p><?php the_title(); ?></p>

<?php
$price     = get_field('price',$post->ID);
$city      = get_the_author_meta('city',$post->post_author );
$country   = get_the_author_meta('country',$post->post_author);

for ( $i = 1 ; $i <= 10 ; $i++ ) {
  $image_id[$i] = get_field('image'.$i,$post->ID);
}

?>

<div class="leftdetai">
<ul>
<li><a class="rentfee">$<?php echo number_format($price) ?>/月　2BR</a></li>
<li><a class="placedetail"><?php echo $city.'/'.$country ?></a></li>
<li style="padding-bottom:10px;"><a><img src="<?php image_path("post/detailwall.png") ?>"/></a></li>
<?php the_content(); ?>
<li><a href="#"><img src="<?php image_path("post/mailbtn.jpg") ?>"/></a></li>
<li><a href="<?php echo get_permalink( get_page_by_title('Responsivepolicy')) ?>"><img src="<?php image_path("post/reservebtn.jpg") ?>"/></a></li>


</ul>
</div>
<div class="roompic">
  <?php echo wp_get_attachment_image($image_id[1], "full",'',array('class' => 'slideimg') )  ?>
<ul>
<?php for ( $i = 1 ; $i <= 10 ; $i++ ) { ?>
  <li style="padding-left:30px;">
    <a href="#" onclick="return false;"><?php echo wp_get_attachment_image( $image_id[$i], "full", '', array('class' => 'thumbnail')) ?></a>
  </li>
<?php } ?>
</ul>
</div>
<div class="authorprof">
<table  class="prof1" width="345"height="327"cellspacing="0" cellpadding="0">
<tr>
<td colspan="2"><table class="inner1" width="335" border="0" cellspacing="0" cellpadding="0">
<tr>
<?php $local_avatars = get_user_meta( $post->post_author, 'simple_local_avatar', true ); ?>
<td style="border:none;"width="125" rowspan="3"><img src="<?php echo $local_avatars['full'] ?>"style="padding-right:15px;"/></td>
<td style="border-top:1px gray dashed;"width="65">名前</td>
<?php
$first_name = get_the_author_meta('first_name',$post->post_author ) ;
$last_name  = get_the_author_meta('last_name',$post->post_author ) ;
?>
<td style="border-top:1px gray dashed;" width="145"><?php echo $first_name.' '.$last_name ?></td>
</tr>
<tr>
<td height="40">性別</td>
<?php $sex = get_the_author_meta('sex',$post->post_author ) ?>
<td><?php echo $set == 1? "女" : "男" ?></td>
</tr>
<tr>
<td height="41">誕生日</td>
<?php
  $year  = get_the_author_meta('birthday1',$post->post_author ) ;
  $month = get_the_author_meta('birthday2',$post->post_author ) ;
  $day   = get_the_author_meta('birthday3',$post->post_author ) ;
  $birthday = new DateTime($year.'-'.$month.'-'.$day);
?>
<td><?php echo $birthday->format('m/d/Y') ?></td>
</tr>
</table>
</td>
</tr>
<tr >
<td  style="border-bottom:1px gray dashed;"class="country"width="96">市</td>
<td  style="border-bottom:1px gray dashed;" width="250"><?php echo get_the_author_meta('city',$post->post_author ) ?></td>
</tr>
<tr>
<td  style="border-bottom:1px gray dashed;" class="country">国</td>
<td  style="border-bottom:1px gray dashed;"><?php echo $country  ?></td>
</tr>
<tr>
<td  style="border-bottom:1px gray dashed;" class="country">大陸</td>
<?php 
  $i = get_the_author_meta('continent',$post->post_author ) ;
  $continents = array(1 => 'アジア', 2 => 'ヨーロッパ', 3 => '北アメリカ', 4 => '南アメリカ',
                                        5 => 'アフリカ', 6 => 'ユーラシア');
?>
<td  style="border-bottom:1px gray dashed;"><?php echo $continents[$i] ?></td>
</tr>
<tr>
<td  style="border-bottom:1px gray dashed;" class="country">海外在住歴</td>
<td  style="border-bottom:1px gray dashed;"><?php echo get_the_author_meta('stay_years',$post->post_author ) ?></td>
</tr>

</table>
<table class="prof2" width="530" height="327"border="0" cellspacing="0" cellpadding="0">
<tr>
<td  style="border-top:1px gray dashed;" width="140" height="40">行ったことがある国</td>
<td  style="border-top:1px gray dashed;" width="417"><?php echo get_the_author_meta('visit_countries',$post->post_author ) ?></td>
</tr>
<tr>
<td height="40">行きたい国</td>
<td><?php echo get_the_author_meta('want_visit_countries',$post->post_author ) ?></td>
</tr>
<tr>
<td height="40">趣味</td>
<td><?php echo get_the_author_meta('hobby',$post->post_author ) ?></td>
</tr>
<tr>
<td height="100">自己紹介</td>
<td><?php echo get_the_author_meta('comment',$post->post_author ) ?></td>
</tr>


</table>
</div>
</div>
</div>

