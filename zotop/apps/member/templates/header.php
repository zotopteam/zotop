{template 'header.php'}
<style type="text/css">
.panel-sidebar{padding:20px;}

.list-sidebar dt{margin-top:20px;margin-bottom:8px;font-weight:700;font-size: 18px;}
.list-sidebar dt:first-child{margin-top:0px;}
.list-sidebar dt a{text-decoration:none;color:#000;}
.list-sidebar dt.active a{color:#0072C6;}

.list-sidebar dd{padding:4px 0 4px 30px;}
.list-sidebar dd a{text-decoration:none;color:#666;}

</style>

<section class="section section-usercenter">	
	<div class="container">
	
		<div class="row">
			<div class="col-sm-2">
				<div class="panel panel-default panel-sidebar">
					<dl class="list-sidebar">
						{loop member_hook::sidebar() $r}							
							<dt {if $r.active}class="active"{/if}><a href="{$r.href}"><i class="icon {$r.icon} fa-fw"></i> {$r.text}</a></dt>
							{if $r.menu and is_array($r.menu)}
								{loop $r.menu $m}
								<dd {if $m.active}class="active"{/if}>
									<a href="{$m.href}">{$m.text}</a>
									<span class="extra">{$m.extra}</span>
								</dd>
								{/loop}
							{/if}
						{/loop}
					</dl>
				</div>			
			</div> <!-- col -->
			<div class="col-sm-10">


