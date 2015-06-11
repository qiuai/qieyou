<script type="text/javascript" src='<?php echo $staticUrl;?>js/zepto.min.js'></script>
<script type="text/javascript" src='<?php echo $staticUrl;?>js/require.min.js'></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/core.wap.js"></script>
<script type="text/javascript">
require.resourceMap({
	res: {
		'widget/pullList': {url: '<?php echo $staticUrl;?>js/widget/pullList.js'},
		'widget/loadPage': {url: '<?php echo $staticUrl;?>js/widget/loadPage.js'},
		'widget/citySelect': {url: '<?php echo $staticUrl;?>js/widget/citySelect.js'},
		'widget/swiper': {url: '<?php echo $staticUrl;?>js/widget/swiper.min.js'},
		'widget/islider': {url: '<?php echo $staticUrl;?>js/islider.js'},
		// 'widget/pictureView': {url: '<?php echo $staticUrl;?>js/widget/pictureView.js', deps: ['widget/swiper']},
		'widget/pictureView': {url: '<?php echo $staticUrl;?>js/widget/pictureView.js'},
		'widget/crop': {url: '<?php echo $staticUrl;?>js/widget/crop.js'},

		'page/index': {url: '<?php echo $staticUrl;?>js/page/index.js', deps: ['widget/swiper']},

		'page/login/index': {url: '<?php echo $staticUrl;?>js/page/login/index.js'},
		'page/login/register': {url: '<?php echo $staticUrl;?>js/page/login/register.js'},
		'page/login/forgetPassword': {url: '<?php echo $staticUrl;?>js/page/login/forgetPassword.js'},

		'page/item/index': {url: '<?php echo $staticUrl;?>js/page/item/index.js', deps: ['widget/swiper', 'widget/pictureView']},
		'page/item/comment': {url: '<?php echo $staticUrl;?>js/page/item/index.js', deps: ['widget/pictureView']},

		'page/order/confirm': {url: '<?php echo $staticUrl;?>js/page/order/confirm.js'},

		'page/home/edituser': {url: '<?php echo $staticUrl;?>js/page/home/edituser.js', deps: ['widget/crop']},
		'page/home/shoucang': {url: '<?php echo $staticUrl;?>js/page/home/shoucang.js', deps: ['widget/swiper']},
		'page/home/userData': {url: '<?php echo $staticUrl;?>js/page/home/userData.js'},
		'page/home/editaddress': {url: '<?php echo $staticUrl;?>js/page/home/editaddress.js'},
		'page/home/editidentify': {url: '<?php echo $staticUrl;?>js/page/home/editidentify.js'},
		'page/home/finance': {url: '<?php echo $staticUrl;?>js/page/home/finance.js'},
		'page/home/pointlist': {url: '<?php echo $staticUrl;?>js/page/home/pointlist.js'},
		'page/home/coupon': {url: '<?php echo $staticUrl;?>js/page/home/coupon.js'},
		'page/home/quan': {url: '<?php echo $staticUrl;?>js/page/home/quan.js', deps: ['widget/swiper']},
		'page/home/order': {url: '<?php echo $staticUrl;?>js/page/home/order.js', deps: ['widget/swiper']},
		'page/home/password': {url: '<?php echo $staticUrl;?>js/page/home/password.js'},
		'page/home/feedback': {url: '<?php echo $staticUrl;?>js/page/home/feedback.js'},
		'page/home/editmobile': {url: '<?php echo $staticUrl;?>js/page/home/editmobile.js'},
		'page/home/editavatar': {url: '<?php echo $staticUrl;?>js/page/home/editavatar.js'},

		'page/user/card': {url: '<?php echo $staticUrl;?>js/page/user/card.js', deps: ['widget/swiper']},
		'page/user/group': {url: '<?php echo $staticUrl;?>js/page/user/group.js', deps: ['widget/swiper']},
		'page/user/wenda': {url: '<?php echo $staticUrl;?>js/page/user/wenda.js', deps: ['widget/swiper', 'widget/pictureView']},
		'page/user/jianren': {url: '<?php echo $staticUrl;?>js/page/user/jianren.js', deps: ['widget/swiper', 'widget/pictureView']},
		'page/user/tour': {url: '<?php echo $staticUrl;?>js/page/user/tour.js', deps: ['widget/swiper', 'widget/pictureView']},
		'page/user/message': {url: '<?php echo $staticUrl;?>js/page/user/message.js', deps: ['widget/swiper']},
		'page/user/taPost': {url: '<?php echo $staticUrl;?>js/page/user/taPost.js', deps: ['widget/swiper', 'widget/pictureView']},

		'page/special': {url: '<?php echo $staticUrl;?>js/page/special/index.js', deps: ['page/index']},
		'page/special/inn': {url: '<?php echo $staticUrl;?>js/page/special/inn.js'},
		'page/special/search': {url: '<?php echo $staticUrl;?>js/page/special/search.js'},

		'page/group/index': {url: '<?php echo $staticUrl;?>js/page/group/index.js', deps: ['page/index', 'widget/swiper', 'widget/pictureView']},
		'page/group/search': {url: '<?php echo $staticUrl;?>js/page/group/search.js', deps: ['widget/pictureView']},
		'page/group/groupDetail': {url: '<?php echo $staticUrl;?>js/page/group/groupDetail.js', deps: ['page/index', 'widget/swiper', 'widget/pictureView']},
		'page/group/settings': {url: '<?php echo $staticUrl;?>js/page/group/settings.js', deps: ['widget/crop']},
		'page/group/adminTopic': {url: '<?php echo $staticUrl;?>js/page/group/adminTopic.js', deps:['widget/pictureView']},
		'page/group/adminMember': {url: '<?php echo $staticUrl;?>js/page/group/adminMember.js', deps: ['widget/swiper']},
		'page/group/member': {url: '<?php echo $staticUrl;?>js/page/group/member.js'},
		'page/group/groupData': {url: '<?php echo $staticUrl;?>js/page/group/groupData.js'},
		'page/group/editavatar': {url: '<?php echo $staticUrl;?>js/page/group/editavatar.js'},
		'page/group/newgroup': {url: '<?php echo $staticUrl;?>js/page/group/newgroup.js', deps: ['widget/crop']},

		'page/forum/post': {url: '<?php echo $staticUrl;?>js/page/forum/post.js'},
		'page/forum/detail': {url: '<?php echo $staticUrl;?>js/page/forum/detail.js', deps: ['widget/pictureView']},
		'page/forum/postdetail': {url: '<?php echo $staticUrl;?>js/page/forum/postdetail.js', deps: ['widget/pictureView']},

		'page/order/payment': {url: '<?php echo $staticUrl;?>js/page/order/payment.js'},
		'page/order/comment': {url: '<?php echo $staticUrl;?>js/page/order/comment.js'},
		'page/order/view': {url: '<?php echo $staticUrl;?>js/page/order/view.js'},

		'page/help/qieyou': {url: '<?php echo $staticUrl;?>js/page/help/qieyou.js'}
	}
});
window.REQUIRE && REQUIRE.MODULE && require.async(REQUIRE.MODULE, REQUIRE.CALLBACK);
</script>