<script type="text/javascript" src='/static/js/zepto.min.js'></script>
<script type="text/javascript" src='/static/js/require.min.js'></script>
<script type="text/javascript" src="/static/js/core.wap.js"></script>
<script type="text/javascript">
require.resourceMap({
	res: {
		'widget/move': {url: './js/move.min.js'},

		'page/finance': {url: './js/page/finance.js'},
		'page/customer': {url: './js/page/customer.js'},
		'page/order': {url: './js/page/order.js'},
	}
});
window.REQUIRE && REQUIRE.MODULE && require.async(REQUIRE.MODULE, REQUIRE.CALLBACK);
</script>