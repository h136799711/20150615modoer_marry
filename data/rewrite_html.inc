# 格式说明：
# 准备替换的正则表达式 （空格） 替换内容 （空格） [参数]
# 正则表达式使用请搜索下网络相关的正则表达式学习资料
# 最后的参数使用[]中括号包围，多个参数用逗号分隔，可使用的值目前有 L,P,C,
# P：解析 URL 行为时进行当前正则表达式替换，如果解析URL时跳过
# C：生成 URL 行为时进行当前正则表达式替换，如果解析URL时跳过。如果P,C都没有写，表示无论解析还是生成都执行
# L：表示如果当前的正则替换成功后，停止下面的其他的替换
# B：如果正则表达式被匹配，则不进行替换就跳出

([a-z]+)\-index\.html 		$1.html 		[C,L]
#([a-z]+)\.html 		$1-index.html	[P,L]

exchange\-gift\-id\-([0-9]+)\.html 		exchange-$1.html 		[C,L]
exchange\-([0-9]+)\.html 		exchange-gift-id-$1.html 		[P,L]

#个人空间相关
space\-index\-uid\-([0-9]+)\.html 		space-$1.html 		[C,L]
space\-([0-9]+)\.html						NULL			[P,B]

#小组相关
group\-([0-9]+)(|-.*)\.html						NULL			[P,B]

([a-z]+)\-detail\-id\-([0-9]+)\.html 		$1-$2.html 		[C,L]
([a-z]+)\-([0-9]+)\.html 		$1-detail-id-$2.html 		[P,L]