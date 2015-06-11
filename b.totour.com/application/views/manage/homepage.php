<h3 class="headline">首页管理</h3>
<div class="tab">
    <ul class="clearfix">
        <li <?php if($action == 'inn') echo "class='current'";?>><a href="<?php echo $baseUrl;?>manage/homepage?act=inn">首页驿栈</a></li>
        <li <?php if($action == 'article') echo "class='current'";?>><a href="<?php echo $baseUrl;?>manage/homepage?act=article">首页完美一天</a></li>
        <li <?php if($action == 'activity') echo "class='current'";?>><a href="<?php echo $baseUrl;?>manage/homepage?act=activity">首页玩法</a></li>
        <li <?php if($action == 'banner') echo "class='current'";?>><a href="<?php echo $baseUrl;?>manage/homepage?act=banner">首页背景图</a></li>
    </ul>
    <span class="more">
        <input class="submit-mini" id="updateSeqBtn" value="保存排序" type="button"/>
    </span>
</div>
<?php if($action == 'inn'):?>
<form class="form" id="updateSeq" method="post" action="<?php echo $baseUrl;?>manage/modify_recommend">
<input type="hidden" value="querySeq" name="class">
<input type="hidden" value="update" name="act">
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5"/>
        <col class="wp10"/>
        <col class="wp15"/>	
        <col class="wp15"/>
        <col class="wp40"/>
        <col class="wp5"/>
        <col class="wp10"/>
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>驿栈老板</th>
        <th>驿栈名称</th>
        <th>链接</th>
        <th>驿栈简介</th>
        <th>显示</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($record as $key => $row):?>
        <tr>
            <td><input type="text" class="w50" name="seq[]" value="<?php echo $row['seq'];?>"><input type="hidden" name="id[]" value="<?php echo $row['id'];?>"/></td>
            <td><?php echo $row['add_content'];?></td>
            <td><?php echo $row['name'];?></td>
            <td><a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['url'];?></a></td>
            <td><?php echo $row['content'];?></td>
            <td><?php if($row['is_show']=='Y') echo '是';else echo '否'?></td>
            <td><a class="editRecommendBtn mr10" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" classid="<?php echo $row['classid'];?>" type="<?php echo $row['class'];?>" isshow="<?php echo $row['is_show'];?>" url="<?php echo $row['url'];?>" name="<?php echo $row['name'];?>" img="<?php echo $row['img'];?>" content="<?php echo $row['content'];?>" add_content="<?php echo $row['add_content'];?>" seq="<?php echo $row['seq'];?>">编辑</a><a class="delRecommendBtn" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" type="<?php echo $row['class'];?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</form>
<?php elseif($action == 'article'):?>
<form class="form" id="updateSeq" method="post" action="<?php echo $baseUrl;?>manage/modify_recommend">
<input type="hidden" value="querySeq" name="class">
<input type="hidden" value="update" name="act">
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5"/>
        <col class="wp25"/>
        <col class="wp15"/>
        <col class="wp40"/>
        <col class="wp5"/>
        <col class="wp10"/>
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>标题</th>
        <th>链接</th>
		<th>简介</th>
        <th>显示</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($record as $key => $row):?>
        <tr>
            <td><input type="text" class="w50" name="seq[]" value="<?php echo $row['seq'];?>"><input type="hidden" name="id[]" value="<?php echo $row['id'];?>"/></td>
            <td><?php echo $row['name'];?></td>
            <td><a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['url'];?></a></td>
            <td><?php echo $row['content'];?></td>
            <td><?php if($row['is_show']=='Y') echo '是';else echo '否'?></td>
            <td><a class="editRecommendBtn mr10" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" classid="<?php echo $row['classid'];?>" type="<?php echo $row['class'];?>" isshow="<?php echo $row['is_show'];?>" url="<?php echo $row['url'];?>" name="<?php echo $row['name'];?>" img="<?php echo $row['img'];?>" content="<?php echo $row['content'];?>" add_content="<?php echo $row['add_content'];?>" seq="<?php echo $row['seq'];?>">编辑</a><a class="delRecommendBtn" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" type="<?php echo $row['class'];?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</form>
<?php elseif($action == 'activity'):?>
<form class="form" id="updateSeq" method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" >
<input type="hidden" value="querySeq" name="class">
<input type="hidden" value="update" name="act">
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5"/>
        <col class="wp25"/>
        <col class="wp15"/>
        <col class="wp40"/>
        <col class="wp5"/>
        <col class="wp10"/>
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
        <th>玩法名称</th>
        <th>链接</th>
		<th>玩法简介</th>
        <th>显示</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($record as $key => $row):?>
        <tr>
            <td><input type="text" class="w50" name="seq[]" value="<?php echo $row['seq'];?>"><input type="hidden" name="id[]" value="<?php echo $row['id'];?>"/></td>
            <td><?php echo $row['name'];?></td>
            <td><a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['url'];?></a></td>
            <td><?php echo $row['content'];?></td>
            <td><?php if($row['is_show']=='Y') echo '是';else echo '否'?></td>
            <td><a class="editRecommendBtn mr10" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" classid="<?php echo $row['classid'];?>" type="<?php echo $row['class'];?>" isshow="<?php echo $row['is_show'];?>" url="<?php echo $row['url'];?>" name="<?php echo $row['name'];?>" img="<?php echo $row['img'];?>" content="<?php echo $row['content'];?>" add_content="<?php echo $row['add_content'];?>" seq="<?php echo $row['seq'];?>">编辑</a><a class="delRecommendBtn" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" type="<?php echo $row['class'];?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</form>
<?php elseif($action == 'banner'):?>
<form class="form" id="updateSeq" method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" >
<input type="hidden" value="querySeq" name="class">
<input type="hidden" value="update" name="act">
<table class="orderList table table-border table-odd">
    <colgroup>
        <col class="wp5"/>
        <col class="wp30"/>
        <col class="wp30"/>
        <col class="wp20"/>
        <col class="wp5"/>
        <col class="wp10"/>
    </colgroup>
    <thead>
    <tr>
        <th>排序</th>
		<th>图片描述</th>
        <th>超链接</th>
		<th>备注（前台不可见）</th>
        <th>显示</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($record as $key => $row):?>
        <tr>
            <td><input type="text" class="w50" name="seq[]" value="<?php echo $row['seq'];?>"><input type="hidden" name="id[]" value="<?php echo $row['id'];?>"/></td>
            <td><?php echo $row['name'];?></td>
            <td><a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['url'];?></a></td>
            <td><?php echo $row['content'];?></td>
            <td><?php if($row['is_show']=='Y') echo '是';else echo '否'?></td>
            <td><a class="editRecommendBtn mr10" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" classid="<?php echo $row['classid'];?>" type="<?php echo $row['class'];?>" isshow="<?php echo $row['is_show'];?>" url="<?php echo $row['url'];?>" name="<?php echo $row['name'];?>" img="<?php echo $row['img'];?>" content="<?php echo $row['content'];?>" add_content="<?php echo $row['add_content'];?>" seq="<?php echo $row['seq'];?>">编辑</a><a class="delRecommendBtn" href="javascript:void(0);" recommendId="<?php echo $row['id'];?>" type="<?php echo $row['class'];?>">删除</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</form>
<?php endif;?>
<div class="pageBar clearfix">
    <p>共<em><?php echo $pageInfo['total']?></em>条 记录， 每页显示<em><?php echo $pageInfo['perpage']?></em>条</p>
    <div class="pages fr" id="page">
    </div>
</div>
<?php if(in_array($action,array('inn','article','activity'))):?>
	<div class="form">
		<label><input type="text" class="w300" value="" id="recommendUrl"></label>
		<label><input class="buttonG" value="添加新推荐" type="button" id="addRecommendBtn"/></label>
		<div class="tips tips-info" id="addRecommendTips">
			<i class="tips-ico"></i>
			<p>请输入驿站前台客栈，完美一天或玩法的URL</p>
		</div>
	</div>
	<div class="addRecommendDom" id="addRecommendInn">
		<h3 class="headline">添加驿栈推荐</h3>
		<form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="addRecommendInnForm" novalidate="novalidate">
			<input type="hidden" value="" name="classid">
			<input type="hidden" value="inn" name="class">
			<input type="hidden" value="create" name="act">
			<table class="form table-form">
				<colgroup>
					<col class="w100">
					<col>
				</colgroup>
				<tbody>
				<tr>
					<td class="leftLabel"><cite>*</cite>链接地址：</td>
					<td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入带http://的前台链接地址</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>驿栈名称：</td>
					<td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>驿栈老板：</td>
					<td><label><input type="text" value="" class="w300" name="add_content"></label><div class="tips" style="display: none;"></div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>驿栈简介：</td>
					<td><label><textarea class="w300" rows="4" cols="" name="content"></textarea></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入首页简介，最多100个中文</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>首页推荐图片：</td>
					<td>
						<p>图片尺寸： 123*167px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
						<div class="ajaxUpload">
							<div class="uploadInfo">
								<div class="uploadBtn">
									<span>请选择图片</span>
									<input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
								</div>
								<div class="progress">
									<span class="progressBar" style="width: 0px;"></span>
									<span class="percent">100%</span>
								</div>
							</div>
							<div class="files"></div>
							<div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
							<input type="hidden" value="" name="img" class="imgUrl" />
						</div>
					</td>
				</tr>   
				<tr>
					<td class="leftLabel">是否显示：</td>
					<td><label><select name="is_show">
							<option value="Y">是</option>
							<option value="N">否</option>
						</select></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">排序：</td>
					<td><label><input type="text" value="" class="w50" name="seq"></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">&nbsp;</td>
					<td><input class="buttonG mr10" id="addRecommendInnSubmit" type="submit" value="提交">
						<input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="addRecommendInnTips" style="display: none;"></div>
					</td>
				</tr>
				</tbody></table>
		</form>
	</div>
	<div class="addRecommendDom perfectDay" id="addRecommendArticle">
		<h3 class="headline">添加完美一天推荐</h3>
		<form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="addRecommendArticleForm" novalidate="novalidate">
			<input type="hidden" value="" name="classid">
			<input type="hidden" value="article" name="class">
			<input type="hidden" value="create" name="act">
			<table class="form table-form">
				<colgroup>
					<col class="w100">
					<col>
				</colgroup>
				<tbody>
				<tr>
					<td class="leftLabel"><cite>*</cite>链接地址：</td>
					<td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入带http://的前台链接地址</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>标题：</td>
					<td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>简介：</td>
					<td><label><textarea class="w300" rows="3" cols="" name="content"></textarea></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入首页简介，最多32个中文</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>首页推荐图片：</td>
					<td>
						<p>图片尺寸： 48*48px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
						<div class="ajaxUpload">
							<div class="uploadInfo">
								<div class="uploadBtn">
									<span>请选择图片</span>
									<input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
								</div>
								<div class="progress">
									<span class="progressBar" style="width: 0px;"></span>
									<span class="percent">100%</span>
								</div>
							</div>
							<div class="files"></div>
							<div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
							<input type="hidden" value="" name="img" class="imgUrl" />
						</div>
					</td>
				</tr> 
				<tr>
					<td class="leftLabel">是否显示：</td>
					<td><label><select name="is_show">
							<option value="Y">是</option>
							<option value="N">否</option>
						</select></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">排序：</td>
					<td><label><input type="text" value="" class="w50" name="seq"></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">&nbsp;</td>
					<td><input class="buttonG mr10" id="addRecommendArticleSubmit" type="submit" value="提交">
						<input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="addRecommendArticleTips" style="display: none;"></div>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
	<div class="addRecommendDom" id="addRecommendActivity">
		<h3 class="headline">添加玩法推荐</h3>
		<form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="addRecommendActivityForm" novalidate="novalidate">
			<input type="hidden" value="" name="classid">
			<input type="hidden" value="activity" name="class">
			<input type="hidden" value="create" name="act">
			<table class="form table-form">
				<colgroup>
					<col class="w100">
					<col>
				</colgroup>
				<tbody>

				<tr>
					<td class="leftLabel"><cite>*</cite>链接地址：</td>
					<td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入带http://的前台链接地址</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>玩法名称：</td>
					<td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>玩法简介：</td>
					<td><label><textarea class="w300" rows="3" cols="" name="content"></textarea></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入首页简介，最多100个中文</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>首页推荐图片：</td>
					<td>
						<p>图片尺寸： 123*167px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
						<div class="ajaxUpload">
							<div class="uploadInfo">
								<div class="uploadBtn">
									<span>请选择图片</span>
									<input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
								</div>
								<div class="progress">
									<span class="progressBar" style="width: 0px;"></span>
									<span class="percent">100%</span>
								</div>
							</div>
							<div class="files"></div>
							<div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
							<input type="hidden" value="" name="img" class="imgUrl" />
						</div>
					</td>
				</tr> 
				<tr>
					<td class="leftLabel">是否显示：</td>
					<td><label>
						<select name="is_show">
							<option value="Y">是</option>
							<option value="N">否</option>
						</select>
						</label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">排序：</td>
					<td><label><input type="text" value="" class="w50" name="seq"></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">&nbsp;</td>
					<td><input class="buttonG mr10" id="addRecommendActivitySubmit" type="submit" value="提交">
						<input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="addRecommendActivityTips" style="display: none;"></div>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
<?php elseif($action=='banner'):?>
	<input class="buttonG" value="添加新背景图" type="button" id="addBannerBtn"/>
	<script>
		var addBannerBtn = $('#addBannerBtn');
		addBannerBtn.click(function(){
			$("#action").val("create");
			$.layer({
				shade : [0.4 , "#000" , true],
				type : 1,
				area : ['auto','auto'],
				title : false,
				offset : ['100px' , '50%'],
				page : {dom : '#editRecommendBannerForm'},
				close : function(index){
					layer.close(index);
				}
			});
		});
	</script>
	<div class="addRecommendDom" id="editRecommendBannerForm">
		<h3 class="headline">编辑首页背景图</h3>
		<form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="editRecommendInnForm" novalidate="novalidate">
			<input type="hidden" value="" name="classid">
			<input type="hidden" value="" name="id">
			<input type="hidden" value="innbanner" name="class">
			<input type="hidden" value="update" name="act" id="action">
			<table class="form table-form">
				<colgroup>
					<col class="w100">
					<col>
				</colgroup>
				<tbody>
				<tr>
					<td class="leftLabel"><cite>*</cite>图片描述：</td>
					<td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>超链接：</td>
					<td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入带http://的链接地址</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite></cite>备注：</td>
					<td><label><textarea class="w300" rows="4" cols="" name="content"></textarea></label>
						<div class="tips tips-info">
							<i class="tips-ico"></i>
							<p>请输入首页简介，最多100个中文</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel"><cite>*</cite>背景图片：</td>
					<td>
						<p>图片大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
						<div class="ajaxUpload">
							<div class="uploadInfo">
								<div class="uploadBtn">
									<span>请选择图片</span>
									<input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
								</div>
								<div class="progress">
									<span class="progressBar" style="width: 0px;"></span>
									<span class="percent">100%</span>
								</div>
							</div>
							<div class="files"></div>
							<div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
							<input type="hidden" value="" name="img" class="imgUrl" />
						</div>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">是否显示：</td>
					<td><label><select name="is_show">
							<option value="Y">是</option>
							<option value="N">否</option>
						</select></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">排序：</td>
					<td><label><input type="text" value="" class="w50" name="seq"></label>
					</td>
				</tr>
				<tr>
					<td class="leftLabel">&nbsp;</td>
					<td><input class="buttonG mr10" id="editRecommendInnSubmit" type="submit" value="提交">
						<input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="editRecommendInnTips" style="display: none;"></div>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
<?php endif;?>
<?php if($action == 'inn'):?>
<div class="addRecommendDom" id="editRecommendInn">
    <h3 class="headline">编辑驿栈推荐</h3>
    <form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="editRecommendInnForm" novalidate="novalidate">
        <input type="hidden" value="" name="classid">
        <input type="hidden" value="" name="id">
        <input type="hidden" value="inn" name="class">
        <input type="hidden" value="update" name="act">
        <table class="form table-form">
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td class="leftLabel"><cite>*</cite>链接地址：</td>
                <td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入带http://的前台链接地址</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>驿栈名称：</td>
                <td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>驿栈老板：</td>
                <td><label><input type="text" value="" class="w300" name="add_content"></label><div class="tips" style="display: none;"></div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>驿栈简介：</td>
                <td><label><textarea class="w300" rows="4" cols="" name="content"></textarea></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入首页简介，最多100个中文</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>首页推荐图片：</td>
                <td>
                    <p>图片尺寸： 123*167px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
                    <div class="ajaxUpload">
                        <div class="uploadInfo">
                            <div class="uploadBtn">
                                <span>请选择图片</span>
                                <input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
                            </div>
                            <div class="progress">
                                <span class="progressBar" style="width: 0px;"></span>
                                <span class="percent">100%</span>
                            </div>
                        </div>
                        <div class="files"></div>
                        <div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
                        <input type="hidden" value="" name="img" class="imgUrl" />
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">是否显示：</td>
                <td><label><select name="is_show">
                        <option value="Y">是</option>
                        <option value="N">否</option>
                    </select></label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">排序：</td>
                <td><label><input type="text" value="" class="w50" name="seq"></label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">&nbsp;</td>
                <td><input class="buttonG mr10" id="editRecommendInnSubmit" type="submit" value="提交">
                    <input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="editRecommendInnTips" style="display: none;"></div>
                </td>
            </tr>
            </tbody>
		</table>
    </form>
</div>
<?php elseif($action == 'article'):?>
<div class="addRecommendDom perfectDay" id="editRecommendArticle">
    <h3 class="headline">编辑完美一天推荐</h3>
    <form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="editRecommendArticleForm" novalidate="novalidate">
        <input type="hidden" value="" name="classid">
        <input type="hidden" value="" name="id">
        <input type="hidden" value="article" name="class">
        <input type="hidden" value="update" name="act">
        <table class="form table-form">
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td class="leftLabel"><cite>*</cite>链接地址：</td>
                <td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入带http://的前台链接地址</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>标题：</td>
                <td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>简介：</td>
                <td><label><textarea class="w300" rows="3" cols="" name="content"></textarea></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入首页简介，最多32个中文</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>首页推荐图片：</td>
                <td>
                    <p>图片尺寸： 48*48px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
                    <div class="ajaxUpload">
                        <div class="uploadInfo">
                            <div class="uploadBtn">
                                <span>请选择图片</span>
                                <input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
                            </div>
                            <div class="progress">
                                <span class="progressBar" style="width: 0px;"></span>
                                <span class="percent">100%</span>
                            </div>
                        </div>
                        <div class="files"></div>
                        <div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
                        <input type="hidden" value="" name="img" class="imgUrl" />
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">是否显示：</td>
                <td><label><select name="is_show">
                            <option value="Y">是</option>
                            <option value="N">否</option>
                        </select></label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">排序：</td>
                <td><label><input type="text" value="" class="w50" name="seq"></label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">&nbsp;</td>
                <td><input class="buttonG mr10" id="editRecommendArticleSubmit" type="submit" value="提交">
                    <input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="editRecommendArticleTips" style="display: none;"></div>
                </td>
            </tr>
            </tbody>
		</table>
    </form>
</div>
<?php elseif($action == 'activity'):?>
<div class="addRecommendDom" id="editRecommendActivity">
    <h3 class="headline">编辑玩法推荐</h3>
    <form method="post" action="<?php echo $baseUrl;?>manage/modify_recommend" id="editRecommendActivityForm" novalidate="novalidate">
        <input type="hidden" value="" name="classid">
        <input type="hidden" value="" name="id">
        <input type="hidden" value="activity" name="class">
        <input type="hidden" value="update" name="act">
        <table class="form table-form">
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>

            <tr>
                <td class="leftLabel"><cite>*</cite>链接地址：</td>
                <td><label><input type="text" value="" autocomplete="off" class="w300" name="url"></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入带http://的前台链接地址</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>玩法名称：</td>
                <td><label><input type="text" value="" class="w300" name="name"></label><div class="tips" style="display: none;"></div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>玩法简介：</td>
                <td><label><textarea class="w300" rows="3" cols="" name="content"></textarea></label>
                    <div class="tips tips-info">
                        <i class="tips-ico"></i>
                        <p>请输入首页简介，最多100个中文</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel"><cite>*</cite>首页推荐图片：</td>
                <td>
                    <p>图片尺寸： 123*167px，大小不超过2MB，允许格式'gif', 'jpg', 'jpeg', 'png'</p>
                    <div class="ajaxUpload">
                        <div class="uploadInfo">
                            <div class="uploadBtn">
                                <span>请选择图片</span>
                                <input class="fileUpload" type="file" accept="image/*" name="imgFile"  />
                            </div>
                            <div class="progress">
                                <span class="progressBar" style="width: 0px;"></span>
                                <span class="percent">100%</span>
                            </div>
                        </div>
                        <div class="files"></div>
                        <div class="showImg"><img src=""><span class="delImage" rel="imgFile" url="">删除</span></div>
                        <input type="hidden" value="" name="img" class="imgUrl" />
                    </div>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">是否显示：</td>
                <td><label>
						<select name="is_show">
                            <option value="Y">是</option>
                            <option value="N">否</option>
                        </select>
					</label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">排序：</td>
                <td><label><input type="text" value="" class="w50" name="seq"></label>
                </td>
            </tr>
            <tr>
                <td class="leftLabel">&nbsp;</td>
                <td><input class="buttonG mr10" id="editRecommendActivitySubmit" type="submit" value="提交">
                    <input class="button mr20 close" type="button" value="取消"><div class="tips tips-ok" id="editRecommendActivityTips" style="display: none;"></div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<?php endif;?>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.validate.extends.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/jquery.ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo $staticUrl;?>js/ajaxUpload.js"></script>
<script type="text/javascript">
$(function(){
	var page = new creatPageObject(<?php echo $pageInfo['curpage'];?>,<?php echo $pageInfo['totalpage'];?>,'<?php echo $pageInfo['url'];?>{page}');
	$('#page').html(page.createPage());

    var addRecommendBtn = $('#addRecommendBtn');    //弹出窗口
    var recommendUrl = $('#recommendUrl');
    var addRecommendTips = $('#addRecommendTips');
    addRecommendBtn.click(function(){
        $.ajax({
            url: "<?php echo $baseUrl;?>manage/analysis_url",
            type: "GET",
            data: {url:recommendUrl.val()},
            success: function(data){
                addRecommendTips.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                if(data.code == '1'){
                    switch(data.msg.class){
                        case 'inn' :
                            fixData("addRecommendInn",data.msg,"inn");
                            $.layer({
                                shade : [0.4 , "#000" , true],
                                type : 1,
                                area : ['auto','auto'],
                                title : false,
                                offset : ['50px' , '50%'],
                                page : {dom : '#addRecommendInn'},
                                close : function(index){
                                    layer.close(index);
                                }
                            });
                            break;
                        case 'article' :
                            fixData("addRecommendArticle",data.msg,"article");
                            $.layer({
                                shade : [0.4 , "#000" , true],
                                type : 1,
                                area : ['auto','auto'],
                                title : false,
                                offset : ['50px' , '50%'],
                                page : {dom : '#addRecommendArticle'},
                                close : function(index){
                                    layer.close(index);
                                }
                            });
                            break;
                        case 'activity':
                            fixData("addRecommendActivity",data.msg,"activity");
                            $.layer({
                                shade : [0.4 , "#000" , true],
                                type : 1,
                                area : ['auto','auto'],
                                title : false,
                                offset : ['50px' , '50%'],
                                page : {dom : '#addRecommendActivity'},
                                close : function(index){
                                    layer.close(index);
                                }
                            });
                            break;
                    }
                }
                else{
                    addRecommendTips.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+data.msg+"</p>").fadeOut(5000);
                }
            }
        });
    });

    var addRecommendInnForm = $('#addRecommendInnForm');
    var addRecommendInnSubmit = $('#addRecommendInnSubmit');
    var addRecommendInnTips = $('#addRecommendInnTips');
    /**添加驿栈推荐前端验证**/
    addRecommendInnForm.validate({
        rules: {
            url:{
                required: true
            },
            name: {
                required: true
            },
            add_content:{
                required: true
            },
            content:{
                required: true,
                stringMaxLength:200
            }
        },
        messages: {
            url:{
                required: "请输入推荐驿栈URL"
            },
            name: {
                required: "请输入驿栈名称"
            },
            add_content:{
                required: "请输入驿栈老板"
            },
            content:{
                required: "请输入驿栈简介",
                stringMaxLength:"简介不能多于100个中文或200个英文"
            }
        }, errorPlacement: function(error, element) {
            var tripEle = element.parent("label").siblings(".tips");
            if(error.text()){
                tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                addRecommendInnSubmit.addClass("disabled").attr("disabled",true);
            }
            else{
                tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                addRecommendInnSubmit.removeClass("disabled").attr("disabled",false);
            }
        },
        ignore:'',
        success:function(label){

        }
    });
    addRecommendInnForm.ajaxForm({
        dataType : 'json',
        success : function(data){
            if(data.code == 1){
                addRecommendInnTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>添加推荐成功！</p>").show().fadeOut(5000);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
            else{
                addRecommendInnTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>"+data.msg+"</p>").show().fadeOut(5000);
            }
        }
    });

    var editRecommendInnForm = $('#editRecommendInnForm');
    var editRecommendInnSubmit = $('#editRecommendInnSubmit');
    var editRecommendInnTips = $('#editRecommendInnTips');
    /**编辑驿栈推荐前端验证**/
    editRecommendInnForm.validate({
        rules: {
            url:{
                required: true
            },
            name: {
                required: true
            },
            add_content:{
                required: true
            },
            content:{
                required: true,
                stringMaxLength:200
            }
        },
        messages: {
            url:{
                required: "请输入推荐驿栈URL"
            },
            name: {
                required: "请输入驿栈名称"
            },
            add_content:{
                required: "请输入驿栈老板"
            },
            content:{
                required: "请输入驿栈简介",
                stringMaxLength:"简介不能多于100个中文或200个英文"
            }
        }, errorPlacement: function(error, element) {
            var tripEle = element.parent("label").siblings(".tips");
            if(error.text()){
                tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                editRecommendInnSubmit.addClass("disabled").attr("disabled",true);
            }
            else{
                tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                editRecommendInnSubmit.removeClass("disabled").attr("disabled",false);
            }
        },
        ignore:'',
        success:function(label){

        }
    });
    editRecommendInnForm.ajaxForm({
        dataType : 'json',
        success : function(data){
            if(data.code == 1){
                editRecommendInnTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>编辑推荐成功！</p>").show().fadeOut(5000);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
            else{
                editRecommendInnTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>"+data.msg+"</p>").show().fadeOut(5000);
            }
        }
    });

    var addRecommendArticleForm = $('#addRecommendArticleForm');
    var addRecommendArticleSubmit = $('#addRecommendArticleSubmit');
    var addRecommendArticleTips = $('#addRecommendArticleTips');
    /**添加完美一天推荐前端验证**/
    addRecommendArticleForm.validate({
        rules: {
            url:{
                required: true
            },
            name:{
                required: true
            },
            content:{
                required: true,
                stringMaxLength:64
            }
        },
        messages: {
            url:{
                required: "请输入推荐完美一天URL"
            },
            name:{
                required: "请输入完美一天标题"
            },
            content:{
                required: "请输入完美一天简介",
                stringMaxLength:"简介不能多于32个中文或64个英文"
            }
        }, errorPlacement: function(error, element) {

            var tripEle = element.parent("label").siblings(".tips");

            if(error.text()){
                tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                addRecommendArticleSubmit.addClass("disabled").attr("disabled",true);
            }
            else{
                tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                addRecommendArticleSubmit.removeClass("disabled").attr("disabled",false);
            }
        },
        ignore:'',
        success:function(label){

        }
    });
    addRecommendArticleForm.ajaxForm({
        dataType : 'json',
        success : function(data){
            if(data.code == 1){
                addRecommendArticleTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>添加推荐成功！</p>").show().fadeOut(5000);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
            else{
                addRecommendArticleTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>"+data.msg+"</p>").show().fadeOut(5000);
            }
        }
    });

    var editRecommendArticleForm = $('#editRecommendArticleForm');
    var editRecommendArticleSubmit = $('#editRecommendArticleSubmit');
    var editRecommendArticleTips = $('#editRecommendArticleTips');
    /**编辑完美一天推荐前端验证**/
    editRecommendArticleForm.validate({
        rules: {
            url:{
                required: true
            },
            name:{
                required: true
            },
            content:{
                required: true,
                stringMaxLength:64
            }
        },
        messages: {
            url:{
                required: "请输入推荐完美一天URL"
            },
            name:{
                required: "请输入完美一天标题"
            },
            content:{
                required: "请输入完美一天简介",
                stringMaxLength:"简介不能多于32个中文或64个英文"
            }
        }, errorPlacement: function(error, element) {

            var tripEle = element.parent("label").siblings(".tips");

            if(error.text()){
                tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                editRecommendArticleSubmit.addClass("disabled").attr("disabled",true);
            }
            else{
                tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                editRecommendArticleSubmit.removeClass("disabled").attr("disabled",false);
            }
        },
        ignore:'',
        success:function(label){

        }
    });
    editRecommendArticleForm.ajaxForm({
        dataType : 'json',
        success : function(data){
            if(data.code == 1){
                editRecommendArticleTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>编辑推荐成功！</p>").show().fadeOut(5000);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
            else{
                editRecommendArticleTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>"+data.msg+"</p>").show().fadeOut(5000);
            }
        }
    });

    var addRecommendActivityForm = $('#addRecommendActivityForm');
    var addRecommendActivitySubmit = $('#addRecommendActivitySubmit');
    var addRecommendActivityTips = $('#addRecommendActivityTips');
    /**添加玩法推荐前端验证**/
    addRecommendActivityForm.validate({
        rules: {
            url:{
                required: true
            },
            name: {
                required: true
            },
            content:{
                required: true,
                stringMaxLength:200
            }
        },
        messages: {
            url:{
                required: "请输入推荐玩法URL"
            },
            name: {
                required: "请输入玩法名称"
            },
            content:{
                required: "请输入玩法简介",
                stringMaxLength:"简介不能多于100个中文或200个英文"
            }
        }, errorPlacement: function(error, element) {

            var tripEle = element.parent("label").siblings(".tips");

            if(error.text()){
                tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                addRecommendActivitySubmit.addClass("disabled").attr("disabled",true);
            }
            else{
                tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                addRecommendActivitySubmit.removeClass("disabled").attr("disabled",false);
            }

        },
        ignore:'',
        success:function(label){

        }
    });
    addRecommendActivityForm.ajaxForm({
        dataType : 'json',
        success : function(data){
            if(data.code == 1){
                addRecommendActivityTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>添加推荐成功！</p>").show().fadeOut(5000);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
            else{
                addRecommendActivityTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>"+data.msg+"</p>").show().fadeOut(5000);
            }
        }
    });

    var editRecommendActivityForm = $('#editRecommendActivityForm');
    var editRecommendActivitySubmit = $('#editRecommendActivitySubmit');
    var editRecommendActivityTips = $('#editRecommendActivityTips');
    /**编辑玩法推荐前端验证**/
    editRecommendActivityForm.validate({
        rules: {
            url:{
                required: true
            },
            name: {
                required: true
            },
            content:{
                required: true,
                stringMaxLength:200
            }
        },
        messages: {
            url:{
                required: "请输入推荐玩法URL"
            },
            name: {
                required: "请输入玩法名称"
            },
            content:{
                required: "请输入玩法简介",
                stringMaxLength:"简介不能多于100个中文或200个英文"
            }
        }, errorPlacement: function(error, element) {

            var tripEle = element.parent("label").siblings(".tips");

            if(error.text()){
                tripEle.show().removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class=\"tips-ico\"></i><p>"+error.text()+"</p>");
                editRecommendActivitySubmit.addClass("disabled").attr("disabled",true);
            }
            else{
                tripEle.show().removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class=\"tips-ico\"></i><p>ok</p>");
                editRecommendActivitySubmit.removeClass("disabled").attr("disabled",false);
            }

        },
        ignore:'',
        success:function(label){

        }
    });
    editRecommendActivityForm.ajaxForm({
        dataType : 'json',
        success : function(data){
            if(data.code == 1){
                editRecommendActivityTips.removeClass("tips-info").removeClass("tips-err").addClass("tips-ok").html("<i class='tips-ico'></i><p>添加推荐成功！</p>").show().fadeOut(5000);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
            else{
                editRecommendActivityTips.removeClass("tips-info").removeClass("tips-ok").addClass("tips-err").html("<i class='tips-ico'></i><p>"+data.msg+"</p>").show().fadeOut(5000);
            }
        }
    });

    var editRecommendBannerForm = $('editRecommendBannerForm');

    /**删除首页推荐**/
    $(".delRecommendBtn").click(function(){
        var recommendId = $(this).attr("recommendId");
        var classType = $(this).attr("type");
        $.ajax({
            url: "<?php echo $baseUrl;?>manage/modify_recommend",
            type: "POST",
            data: {class:classType,act:"delete",id:recommendId},
            success: function(data){
                if(data.code == 1){
                    layer.alert("推荐删除成功",1,"操作成功",function(){
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

    /**编辑首页弹出层**/
    $(".editRecommendBtn").click(function(){
        var type = $(this).attr("type");
        var _this = this;
        switch(type){
            case 'inn' :
                fixEditData(_this,"editRecommendInn","inn");
                $.layer({
                    shade : [0.4 , "#000" , true],
                    type : 1,
                    area : ['auto','auto'],
                    title : false,
                    offset : ['50px' , '50%'],
                    page : {dom : '#editRecommendInn'},
                    close : function(index){
                        layer.close(index);
                    }
                });
                break;
            case 'article' :
                fixEditData(_this,"editRecommendArticle","article");
                $.layer({
                    shade : [0.4 , "#000" , true],
                    type : 1,
                    area : ['auto','auto'],
                    title : false,
                    offset : ['50px' , '50%'],
                    page : {dom : '#editRecommendArticle'},
                    close : function(index){
                        layer.close(index);
                    }
                });
                break;
            case 'activity':
                fixEditData(_this,"editRecommendActivity","activity");
                $.layer({
                    shade : [0.4 , "#000" , true],
                    type : 1,
                    area : ['auto','auto'],
                    title : false,
                    offset : ['50px' , '50%'],
                    page : {dom : '#editRecommendActivity'},
                    close : function(index){
                        layer.close(index);
                    }
                });
                break;
        }
    });

    /**保存排序**/
    var updateSeq = $('#updateSeq');
    $('#updateSeqBtn').click(function(){
        updateSeq.submit();
    });
    updateSeq.validate({
        rules: {
            "seq[]":{
                required: true,
                digits:true
            }
        },
        messages: {
            "seq[]":{
                required: "请输入整数",
                digits:"请输入整数"
            }
        }, errorPlacement: function(error, element) {

            if(error.text()){

            }
            else{

            }

        },
        ignore:'',
        success:function(label){

        }
    });
    updateSeq.ajaxForm({
        success: function(data){
            if(data.code == 1){
                layer.alert("修改排序成功",1,"操作成功",function(){
                        window.location.reload();
                    }
                );
            }
            else{
                layer.alert(data.msg ,3,"提示");
            }
        }
    });

    /**添加首页推荐弹出层填充**
     * @param id 弹出层ID
     * @param data 数据
     * @param type 类型
     */
    function fixData(id,data,type){
        var dom = $('#'+id);
        var classId = dom.find("input[name='classid']");
        var url = dom.find("input[name='url']");
        var name = dom.find("input[name='name']");
        var content = dom.find("textarea[name='content']");
        var seq = dom.find("input[name='seq']");
        var imgUrl = dom.find(".imgUrl");
        var showImg = dom.find(".showImg");
        if(type=="inn"){
            var add_content = dom.find("input[name='add_content']");
            add_content.val(data.add_content);
        }
        classId.val(data.classid);
        url.val(data.url);
        name.val(data.name);
        content.val(data.content);
        seq.val(data.seq);
        imgUrl.val(data.img);
        showImg.html("<img src='<?php echo $attachUrl;?>"+data.img+"'><span class='delImage' rel='imgFile' url='"+data.img+"'>删除</span>");
    }

    /**编辑首页推荐弹出层填充**
     *
     * @param obj   编辑按钮
     * @param id   弹出层DOM
     * @param type  类型
     */
    function fixEditData(obj,id,type){
        var dom = $('#'+id);
        var recommendId = dom.find("input[name='id']");
        var classId = dom.find("input[name='classid']");
        var url = dom.find("input[name='url']");
        var name = dom.find("input[name='name']");
        var content = dom.find("textarea[name='content']");
        var seq = dom.find("input[name='seq']");
        var isshow = dom.find("select[name='is_show']");
        var imgUrl = dom.find(".imgUrl");
        var showImg = dom.find(".showImg");
        if(type=="inn"){
            var add_content = dom.find("input[name='add_content']");
            add_content.val($(obj).attr("add_content"));
        }
        recommendId.val($(obj).attr("recommendId"));
        classId.val($(obj).attr("classid"));
        url.val($(obj).attr("url"));
        name.val($(obj).attr("name"));
        content.val($(obj).attr("content"));
        seq.val($(obj).attr("seq"));
        imgUrl.val($(obj).attr("img"));
        showImg.html("<img src='<?php echo $attachUrl;?>"+$(obj).attr("img")+"'><span class='delImage' rel='imgFile' url='"+$(obj).attr("img")+"'>删除</span>");
        if($(obj).attr("isshow") == "Y"){
            isshow.find("option[value='Y']").attr("selected","selected");
            isshow.find("option[value='N']").removeAttr("selected");
        }
        else{
            isshow.find("option[value='N']").attr("selected","selected");
            isshow.find("option[value='Y']").removeAttr("selected");
        }
    }
});
</script>