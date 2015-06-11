<h3 class="headline">完美一天管理</h3>
<div class="filter mb20">
    <form method="get">
    <div class="form p10-20">
        <table class="form table-form">
            <colgroup>
                <col class="w80">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td class="leftLabel">选择目的地：</td>
                <td>
                    <label>
                        <select id="destList" name="tid">
                            <option  value="0">所有目的地</option>
                            <?php foreach($destList as $key => $dest):?>
                                <option <?php if($dest_id == $dest['dest_id']) echo 'selected';?> value="<?php echo $dest['dest_id'];?>"><?php echo $dest['dest_name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </label>
                </td>
            </tr>
                <tr>
                    <td class="leftLabel">选择类型：</td>
                    <td>
                        <label><input type="radio" class="radio" name="search_type" value="uname" <?php if($search_type == 'uname') echo 'checked="checked"';?>>作者</label>
                        <label><input type="radio" class="radio" name="search_type" value="title" <?php if($search_type == 'title') echo 'checked="checked"';?>>标题</label>
                    </td>
                </tr>
                <tr>
                    <td class="leftLabel">关键字：</td>
                    <td>
                        <label><input name="keyword" type="text"  value="<?php echo $keyword?$keyword:'请输入关键字';?>" onfocus="doempty(this, '请输入关键字');" onblur="doempty(this, '请输入关键字');"></label>
                    </td>
                </tr>
                <tr>
                    <td class="leftLabel">&nbsp;</td>
                    <td>
                        <label><input type="submit" value="查询" class="buttonG"></label>
                    </td>
                </tr>

            </tbody>
        </table>
    </form>
    </div>
</div>
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp20"/>
        <col class="wp15"/>
        <col class="wp15"/>
        <col class="wp10"/>
        <col class="wp10"/>
        <col class="wp15"/>
        <col class="wp15"/>
    </colgroup>
    <thead>
    <tr>
        <th>完美一天标题</th>
        <th>发布时间</th>
        <th>更新时间</th>
        <th>赞</th>
        <th>作者</th>
		<th>当前发布状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($articleList as $key => $article):?>
        <tr>
            <td><a target="_blank" href="<?php echo $front_baseUrl.'perfectday/view/'.$article['article_id'];?>"><?php echo $article['article_title'];?></a></td>
            <td><?php echo date('Y-m-d',$article['create_time']);?></td>
            <td><?php echo date('Y-m-d',$article['update_time']);?></td>
            <td><?php echo $article['article_likes'];?></td>
            <td><?php echo $article['user_name'];?></td>
            <td><?php echo $article['state'] =='published'?'已发布':'未发布';?></td>
            <td><a class="remarkArticle mr10" href="javascript:void(0);"  articleId="<?php echo $article['article_id'];?>">标记为未发布</a><a class="delArticle" href="javascript:void(0);"  articleId="<?php echo $article['article_id'];?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<script type="text/javascript">
function doempty(o, str){
	var val = o.value;
	if(val == str){
		o.value = '';
	}else if(val == ''){
		o.value = str;
	}
}
$(function(){
	var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
	$('#page').html(page.createPage());
	var destSelect = $('#destList');
	destSelect.change(function(){
		var destVal = destSelect.val();
		if (destVal!=''){
			window.location.href="<?php echo $baseUrl;?>manage/articles?tid="+destVal<?php if($keyword) echo '+"&search_type='.$search_type.'&keyword='.$keyword.'"';?>;
		}
	});

    /**标记为未发布**/
    $('.remarkArticle').click(function(){
        var articleId = $(this).attr("articleId");
        $.ajax({
            url:"<?php echo $baseUrl.'manage/article_edit';?>",
            type: "POST",
            data: {act:"reback",aid:articleId},
            success: function(data){
                if(data.code == 1){
                    layer.alert("标记文章成功",1,"操作成功",function(){
                            window.location.reload();
                        }
                    );
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        })
    });

    /**删除文章**/
    $('.delArticle').click(function(){
        var articleId = $(this).attr("articleId");
        $.ajax({
            url:"<?php echo $baseUrl.'manage/article_edit';?>",
            type: "POST",
            data: {act:"delete",aid:articleId},
            success: function(data){
                if(data.code == 1){
                    layer.alert("删除文章成功",1,"操作成功",function(){
                            window.location.reload();
                        }
                    );
                }
                else{
                    layer.alert(data.msg ,3,"提示");
                }
            }
        })
    })
});
</script>